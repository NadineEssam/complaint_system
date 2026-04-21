<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OfficesSavedComplaintsCountReport implements ReportInterface
{
    public function permission(): string
    {
        return 'reports.view-report-offices-saved-complaints-count-report';
    }

    public function label(): string
    {
        return ' تقرير مركزى عن عدد الشكاوى المحفوظ بالنسبة للمكاتب ';
    }

    public function key(): string
    {
        return 'offices-saved-complaints-count-report';
    }

    public function filters(): array
    {


     $request_type = DB::table('requesttype')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->requesttypeid  => $item->requesttypename];
            })
            ->toArray();
        $request_type[0] = 'الكل';

      

        $request_source = DB::table('comsources')
            ->get()
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
                'name'        => 'request_type',
                'label'       => 'نوع الطلب',
                'type'        => 'select',
                'options'     =>  $request_type,
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


       $data = DB::table('office as o')
        ->leftJoin('sfdcomplaints as c', function ($join) use ($filters) {
            $join->on('o.id', '=', 'c.office');
            $join->where('c.ComplaintStatus', 4 ); // Only count saved complaints

            // Apply filters INSIDE join to preserve LEFT JOIN behavior
            if (!empty($filters['date_from'])) {
                $join->where('c.ComplaintDate', '>=', $filters['date_from'] );
            }

            if (!empty($filters['date_to'])) {
                $join->where('c.ComplaintDate', '<=', $filters['date_to'] );
            }
            if (!empty($filters['request_type']) && $filters['request_type'] != '0') {
                $join->where('c.RequestType', $filters['request_type']);
            }

            if (!empty($filters['request_source']) && $filters['request_source'] != '0') {
                $join->where('c.ComplaintSources', $filters['request_source']);
            }
        })
        ->select(
            'o.id as office',
            'o.REG_OFFIC_NAMA as office_name',

            DB::raw("SUM(CASE WHEN c.fk_close_reason_id = 2 THEN 1 ELSE 0 END) as for_company_count"),
            DB::raw("SUM(CASE WHEN c.fk_close_reason_id = 1 THEN 1 ELSE 0 END) as for_client_count")
        )
        ->groupBy('o.id', 'o.REG_OFFIC_NAMA')
        ->get();

        return $data;
    }



    public function headings(): array
    {
        return [
       
            'بسبب الجهاز ',
            'بسبب عميل',
            'المكتب ',
        ];
    }

    public function map(mixed $row): array
    {
        return [
       
            $row->for_company_count ?? 0,
            $row->for_client_count ?? 0,
            $row->office_name ?? '',

        ];
    }
}
