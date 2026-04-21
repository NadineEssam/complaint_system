<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ComplaintsAndInquiriesSummaryBySource implements ReportInterface
{
    public function permission(): string
    {
        return 'reports.view-report-complaints-inquiries-summary-by-source';
    }

    public function label(): string
    {
        return ' بيان مختصر بإجمالى عدد ( الشكاوى / الإستفسارات ) بالنسبه للمصدر ';
    }

    public function key(): string
    {
        return 'complaints-inquiries-summary-by-source';
    }

    public function filters(): array
    {
        return [
            [
                'name'        => 'date_from',
                'label'       => 'من تاريخ',
                'type'        => 'date',
                'required'    => false,
            ],
            [
                'name'        => 'date_to',
                'label'       => 'إلى تاريخ',
                'type'        => 'date',
                'required'    => false,
            ],

        ];
    }

    public function generate(array $filters): mixed
    {

        $data = DB::table('sfdcomplaints')
            ->leftJoin('comsources', 'comsources.comsourcesid', '=', 'sfdcomplaints.ComplaintSources')
            ->select(
                'comsources.comsourcesid',
                'comsources.comsourcesname',
                DB::raw('COUNT(sfdcomplaints.ComplaintID) as total_count'),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '2' THEN 1 END) as complaint_count"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '1' THEN 1 END) as inquiry_count")
            )
            ->when($filters['date_from'] ?? null, function ($query, $date_from) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '>=', $date_from);
            })
            ->when($filters['date_to'] ?? null, function ($query, $date_to) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '<=', $date_to);
            })
            ->groupBy('comsources.comsourcesid', 'comsources.comsourcesname')
            ->get();

        return $data;
    }



    public function headings(): array
    {
        return ['عدد الاستفسارات', 'عدد الشكاوى', 'إجمالي العدد',  'اسم المصدر'];
    }

    public function map(mixed $row): array
    {
        return [

            $row->inquiry_count,
            $row->complaint_count,
            $row->total_count,
            $row->comsourcesname ?? 'غير محدد',

        ];
    }
}
