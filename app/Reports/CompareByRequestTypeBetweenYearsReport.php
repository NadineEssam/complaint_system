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
        return ' تقرير سنوى بعدد الشكاوى والاستفسارات الوارده ';
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
                'label'       => 'العام الاول',
                'type'        => 'number',
                'required'    => true,
            ],
            [
                'name'        => 'second_year',
                'label'       => 'العام الثانى',
                'type'        => 'number',
                'required'    => true,
            ],

        ];
    }

    public function generate(array $filters): mixed
    {
        $this->filters = $filters;
        $first_year  = $filters['first_year'];
        $second_year = $filters['second_year'];


        $data = DB::table('sfdcomplaints as c')
            ->leftJoin('office as o', 'o.id', '=', 'c.office')
            ->select(

                'c.office',
                'o.REG_OFFIC_NAMA as office_name',

                DB::raw("COUNT(CASE 
            WHEN RequestType = 2 
            AND YEAR(ComplaintDate) = $first_year
        THEN 1 END) as complaints_first_year"),

                DB::raw("COUNT(CASE 
            WHEN RequestType = 1 
            AND YEAR(ComplaintDate) = $first_year
        THEN 1 END) as inquiries_first_year"),

                DB::raw("COUNT(CASE 
            WHEN RequestType = 2 
            AND YEAR(ComplaintDate) = $second_year
        THEN 1 END) as complaints_second_year"),

                DB::raw("COUNT(CASE 
            WHEN RequestType = 1 
            AND YEAR(ComplaintDate) = $second_year
        THEN 1 END) as inquiries_second_year")
            )
            ->groupBy('c.office', 'o.REG_OFFIC_NAMA')
            ->get();

        return $data;
    }



    public function headings(): array
    {
        return ['المكتب', 'عدد شكاوى لعام ' . $this->filters['first_year'], 'عدد شكاوى لعام ' . $this->filters['second_year'], 'عدد استفسارات لعام ' . $this->filters['first_year'], 'عدد استفسارات لعام ' . $this->filters['second_year']];
    }

    public function map(mixed $row): array
    {
        return [
            $row->office_name,
            $row->complaints_first_year,
            $row->complaints_second_year,
            $row->inquiries_first_year,
            $row->inquiries_second_year
        ];
    }
}
