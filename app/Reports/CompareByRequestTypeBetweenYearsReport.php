<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompareByRequestTypeBetweenYearsReport implements ReportInterface
{

    private array $filters = [];

    public function permission(): string
    {
        return 'reports.view-report-compare-request-type-between-years';
    }

    public function label(): string
    {
        return ' تقرير سنوى بعدد الطلبات الوارده وانوعها ';
    }

    public function key(): string
    {
        return 'compare-request-type-between-years';
    }

    public function filters(): array
    {
        return [
            [
                'name'        => 'first_year',
                'label'       => 'العام ',
                'type'        => 'number',
                'required'    => true,
            ],
            // [
            //     'name'        => 'second_year',
            //     'label'       => 'العام الثانى',
            //     'type'        => 'number',
            //     'required'    => true,
            // ],

        ];
    }

    public function generate(array $filters): mixed
    {
        $this->filters = $filters;
        $first_year  = $filters['first_year'];
        //$second_year = $filters['second_year'];


        $data = DB::table('sfdcomplaints as c')
            ->leftJoin('ben.OFFICE as o', 'o.id', '=', 'c.office')
            ->select(

                'c.office',
                'o.REG_OFFIC_NAMA as office_name',

                DB::raw("COUNT(CASE 
                    WHEN RequestType = 2 AND valid = 1  
                    AND YEAR(ComplaintDate) = $first_year
                THEN 1 END) as complaints_first_year"),

                DB::raw("COUNT(CASE 
                    WHEN RequestType = 1 AND valid = 1
                    AND YEAR(ComplaintDate) = $first_year
                THEN 1 END) as inquiries_first_year"),



                DB::raw("COUNT(CASE 
                    WHEN RequestType = 4 AND valid = 1
                    AND YEAR(ComplaintDate) = $first_year
                THEN 1 END) as suggestions_first_year"),

                DB::raw("COUNT(CASE 
                    WHEN RequestType = 5 AND valid = 1
                    AND YEAR(ComplaintDate) = $first_year
                THEN 1 END) as request_first_year"),


                //         DB::raw("COUNT(CASE 
                //     WHEN RequestType = 2 AND valid = 1
                //     AND YEAR(ComplaintDate) = $second_year
                // THEN 1 END) as complaints_second_year"),

                //         DB::raw("COUNT(CASE 
                //     WHEN RequestType = 1 AND valid = 1
                //     AND YEAR(ComplaintDate) = $second_year
                // THEN 1 END) as inquiries_second_year")
            )
            ->where('c.valid', 1)
            ->where('c.office', '!=', 0)
            ->groupBy('c.office', 'o.REG_OFFIC_NAMA')
            ->get();

        // Add total row
        $data->push((object) [
            'office' => null,
            'office_name' => 'المجموع',
            'complaints_first_year' => $data->sum('complaints_first_year'),
            'inquiries_first_year' => $data->sum('inquiries_first_year'),
            'suggestions_first_year' => $data->sum('suggestions_first_year'),
            'request_first_year' => $data->sum('request_first_year'),
        ]);
            
        return $data;
        
    }



    public function headings(): array
    {
        return [
            'الفرع',
            'عدد شكوى عامة لعام ' . $this->filters['first_year'],

            'عدد استفسارات لعام ' . $this->filters['first_year'],
            'عدد مقترحات لعام ' . $this->filters['first_year'],
            'عدد طلب رواد الأعمال لعام ' . $this->filters['first_year'],


        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->office_name,
            $row->complaints_first_year,
            $row->inquiries_first_year,
            $row->suggestions_first_year,
            $row->request_first_year,
        ];
    }
}
