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
        return ' مقارنه سنويه للمصادر بالنسبه لعدد الطلبات وانوعها';
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
            ->leftJoin('complaint_sources', 'complaint_sources.complaint_id', '=', 'sfdcomplaints.complaintid')
            ->leftJoin('comsources', 'comsources.comsourcesid', '=', 'complaint_sources.comsource_id')
            ->select(
                'comsources.comsourcesid',
                'comsources.comsourcesname',
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '2' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $first_year THEN 1 END) as complaints_first_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '1' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $first_year THEN 1 END) as inquiries_first_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '3' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $first_year THEN 1 END) as direct_complaints_first_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '4' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $first_year THEN 1 END) as suggestions_first_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '5' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $first_year THEN 1 END) as requests_first_year"),

                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '2' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $second_year THEN 1 END) as complaints_second_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '1' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $second_year THEN 1 END) as inquiries_second_year") ,
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '3' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $second_year THEN 1 END) as direct_complaints_second_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '4' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $second_year THEN 1 END) as suggestions_second_year"),
                DB::raw("COUNT(CASE WHEN sfdcomplaints.RequestType = '5' AND sfdcomplaints.valid = 1 AND YEAR(sfdcomplaints.ComplaintDate) = $second_year THEN 1 END) as requests_second_year"),

            )
            ->where('sfdcomplaints.valid', 1)

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
            'عدد شكوى عامة لعام ' . $this->filters['first_year'],
            'عدد استفسارات لعام ' . $this->filters['first_year'],
            'عدد شكاوى موجهة لعام ' . $this->filters['first_year'],
            'عدد مقترحات لعام ' . $this->filters['first_year'],
            'عدد طلبات رواد الأعمال لعام ' . $this->filters['first_year'],
            'عدد شكوى عامة لعام ' . $this->filters['second_year'],
            'عدد استفسارات لعام ' . $this->filters['second_year'],
            'عدد شكاوى موجهة لعام ' . $this->filters['second_year'],
            'عدد مقترحات لعام ' . $this->filters['second_year'],
            'عدد طلبات رواد الأعمال لعام ' . $this->filters['second_year'],
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->comsourcesname ?? 'غير محدد',
            $row->complaints_first_year,
            $row->inquiries_first_year,
            $row->direct_complaints_first_year,
            $row->suggestions_first_year,
            $row->requests_first_year,
            $row->complaints_second_year,
            $row->inquiries_second_year,
            $row->direct_complaints_second_year,
            $row->suggestions_second_year,
            $row->requests_second_year,
        ];
    }
}
