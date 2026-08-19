<?php

namespace App\Reports;

use App\Models\CompStatus;
use App\Models\ComSource;
use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OfficesComplaintsAndInquiriesSummaryReport implements ReportInterface
{
    public function permission(): string
    {
        return 'reports.view-report-offices-complaints-and-inquiries-summary-report';
    }

    public function label(): string
    {
        return '  تقرير مركزى عن عدد الطلبات الواردة للمكاتب وانوعها ';
    }

    public function key(): string
    {
        return 'offices-complaints-and-inquiries-summary-report';
    }

    public function filters(): array
    {


        $request_status = CompStatus::all()
            ->mapWithKeys(function ($item) {
                return [$item->statusID  => $item->statusText];
            })
            ->toArray();
        $request_status[0] = 'الكل';

        $request_source = ComSource::all()
            ->mapWithKeys(function ($item) {
                return [$item->comsourcesid  => $item->comsourcesname];
            })
            ->toArray();
        $request_source[0] = 'الكل';


        return [
            [
                'name'        => 'date_from',
                'label'       => 'من تاريخ',
                'type'        => 'date',
                'required'    => true,
            ],
            [
                'name'        => 'date_to',
                'label'       => 'إلى تاريخ',
                'type'        => 'date',
                'required'    => true,
            ],
            [
                'name'        => 'request_status',
                'label'       => 'حالة الطلب',
                'type'        => 'select',
                'options'     =>  $request_status,
                'required'    => false,
                'default'     => '0',
            ],
            [
                'name'        => 'request_source',
                'label'       => 'مصدر الطلب',
                'type'        => 'select',
                'options'     =>  $request_source,
                'required'    => false,
                'default'     => '0',
            ],


        ];
    }

    public function generate(array $filters): mixed
    {


        $data = DB::table('ben.OFFICE as o')
            ->leftJoin('sfdcomplaints as c', function ($join) use ($filters) {
                $join->on('o.id', '=', 'c.office');
                $join->where('c.valid', 1);
                // Apply filters INSIDE join to preserve LEFT JOIN behavior
                if (!empty($filters['date_from'])) {
                    $join->where('c.ComplaintDate', '>=', $filters['date_from']);
                }

                if (!empty($filters['date_to'])) {
                    $join->where('c.ComplaintDate', '<=', $filters['date_to']);
                }

                if (!empty($filters['request_status']) && $filters['request_status'] != '0') {
                    $join->where('c.ComplaintStatus', $filters['request_status']);
                }

                if (!empty($filters['request_source']) && $filters['request_source'] != '0') {
                    $join->where('c.ComplaintSources', $filters['request_source']);
                }
            })
            ->select(
                'o.id as office',
                'o.REG_OFFIC_NAMA as office_name',

                DB::raw("SUM(CASE WHEN c.RequestType = 2 AND c.valid = 1 THEN 1 ELSE 0 END) as complaints_count"),
                DB::raw("SUM(CASE WHEN c.RequestType = 1 AND c.valid = 1 THEN 1 ELSE 0 END) as inquiries_count"),
                DB::raw("SUM(CASE WHEN c.RequestType = 4 AND c.valid = 1 THEN 1 ELSE 0 END) as suggestions_count"),
                DB::raw("SUM(CASE WHEN c.RequestType = 5 AND c.valid = 1 THEN 1 ELSE 0 END) as requests_count")
            )
            ->groupBy('o.id', 'o.REG_OFFIC_NAMA')
            ->get();

              // Add total row
        $data->push((object) [
            'office' => null,
            'office_name' => 'المجموع',

            'complaints_count' => $data->sum('complaints_count'),
            'inquiries_count' => $data->sum('inquiries_count'),
            'suggestions_count' => $data->sum('suggestions_count'),
            'requests_count' => $data->sum('requests_count'),
        ]);

        return $data;
    }



    public function headings(): array
    {
        return [


            'الفرع ',
            'عدد شكوى عامة',
            'عدد الإستفسارات',
            'عدد مقترحات',
            'عدد طلبات رواد الأعمال',




        ];
    }

    public function map(mixed $row): array
    {
        return [
             $row->office_name ?? '',
            $row->complaints_count ?? 0,

            $row->inquiries_count ?? 0,
            $row->suggestions_count ?? 0,
            $row->requests_count ?? 0,
           

        ];
    }
}
