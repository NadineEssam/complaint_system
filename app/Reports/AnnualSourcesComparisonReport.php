<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnnualSourcesComparisonReport implements ReportInterface
{

    private array $filters = [];

    public function permission(): string
    {
        return 'reports.view-report-annual-sources-comparison';
    }

    public function label(): string
    {
        return ' مقارنه سنويه للمصادر فى الشكاوى والاستفسارات';
    }

    public function key(): string
    {
        return 'annual-sources-comparison';
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

        $data = DB::table('sfdcomplaints')
            ->leftJoin('comsources', 'comsources.comsourcesid', '=', 'sfdcomplaints.ComplaintSources')
            ->select(
                'comsources.comsourcesid',
                'comsources.comsourcesname',
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '2' AND YEAR(ComplaintDate) = $first_year THEN 1 END) as complaints_first_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '1' AND YEAR(ComplaintDate) = $first_year THEN 1 END) as inquiries_first_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '2' AND YEAR(ComplaintDate) = $second_year THEN 1 END) as complaints_second_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '1' AND YEAR(ComplaintDate) = $second_year THEN 1 END) as inquiries_second_year")
            )
            ->groupBy('comsources.comsourcesid', 'comsources.comsourcesname')
            ->get();


        return $data;
    }



    public function headings(): array
    {
        return [
            'عدد استفسارات لعام ' . $this->filters['second_year'],
            'عدد شكاوى لعام ' . $this->filters['second_year'],
            'عدد استفسارات لعام ' . $this->filters['first_year'],
            'عدد شكاوى لعام ' . $this->filters['first_year'],
            'اسم المصدر',
        ];
    }

    public function map(mixed $row): array
    {
        return [

            $row->inquiries_second_year,
            $row->complaints_second_year,
            $row->inquiries_first_year,
            $row->complaints_first_year,
            $row->comsourcesname ?? 'غير محدد',
        ];
    }
}
