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
        return ' بيان مختصر بإجمالى عدد الطلبات وانوعها بالنسبه للمصدر ';
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
            ->leftJoin('complaint_sources', 'complaint_sources.complaint_id', '=', 'sfdcomplaints.ComplaintID')
            ->leftJoin('comsources', 'comsources.comsourcesid', '=', 'complaint_sources.comsource_id')
            ->select(
                'comsources.comsourcesid',
                'comsources.comsourcesname',
                DB::raw('COUNT(CASE WHEN  sfdcomplaints.valid = 1  THEN 1 END) as total_count'),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '2' AND sfdcomplaints.valid = 1 THEN 1 END) as complaint_count"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '1' AND sfdcomplaints.valid = 1 THEN 1 END) as inquiry_count"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '3' AND sfdcomplaints.valid = 1 THEN 1 END) as direct_complaint_count"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '4' AND sfdcomplaints.valid = 1 THEN 1 END) as suggestion_count"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '5' AND sfdcomplaints.valid = 1 THEN 1 END) as request_count"),
            )
            ->where('sfdcomplaints.valid', 1)
            ->when($filters['date_from'] ?? null, function ($query, $date_from) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '>=', $date_from);
            })
            ->when($filters['date_to'] ?? null, function ($query, $date_to) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '<=', $date_to);
            })
            ->groupBy(
                'comsources.comsourcesid',
                'comsources.comsourcesname'
            )
            ->get();

        return $data;
    }



    public function headings(): array
    {
        return [
            'اسم المصدر',
            'إجمالي العدد',
            'عدد شكوى عامة',
            'عدد الاستفسارات',
            'عدد الشكاوى الموجهة',
            'عدد المقترحات',
            'عدد طلبات رواد الأعمال',
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->comsourcesname ?? 'غير محدد',
            $row->total_count,
            $row->complaint_count,
            $row->inquiry_count,
            $row->direct_complaint_count,
            $row->suggestion_count,
            $row->request_count,
        ];
    }
}
