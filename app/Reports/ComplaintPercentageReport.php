<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ComplaintPercentageReport implements ReportInterface
{
    public function permission(): string
    {
        return 'view-report-complaint-percentage-report';
    }

    public function label(): string
    {
        return ' تقرير بأعلى الشكاوى الوارده من حيث التصنيف ';
    }

    public function key(): string
    {
        return 'complaint-percentage-report';
    }

    public function filters(): array
    {





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
  




        ];
    }

    public function generate(array $filters): mixed
    {

        $totalComplaints = DB::table('sfdcomplaints as s')
            ->when(
                !empty($filters['date_from']),
                fn($q) =>
                $q->whereDate('s.ComplaintDate', '>=', $filters['date_from'])
            )
            ->when(
                !empty($filters['date_to']),
                fn($q) =>
                $q->whereDate('s.ComplaintDate', '<=', $filters['date_to'])
            )
          
            ->count();

        $data = DB::table('complainttype')
            ->leftJoin('requesttype', 'requesttype.requesttypeid', '=', 'complainttype.request_fk')
            ->leftJoin('sfdcomplaints as s', function ($join) use ($filters) {
                $join->on('s.ComplaintType', '=', 'complainttype.comtypeid');


                if (!empty($filters['date_from'])) {
                    $join->whereDate('s.ComplaintDate', '>=', $filters['date_from']);
                }

                if (!empty($filters['date_to'])) {
                    $join->whereDate('s.ComplaintDate', '<=', $filters['date_to']);
                }

              
            })
            ->select(
                'complainttype.comtypeid',
                'complainttype.comtypename',
                'requesttype.requesttypeid',
                'requesttype.requesttypename',

                DB::raw('COUNT(s.ComplaintID) as complaints_count'),
                DB::raw("ROUND((COUNT(s.ComplaintID) / {$totalComplaints}) * 100, 2) as complaints_percentage"),


                DB::raw("SUM(CASE WHEN s.ComplaintStatus = 4 THEN 1 ELSE 0 END) as saved_count"),
                DB::raw("ROUND((SUM(CASE WHEN s.ComplaintStatus = 4 THEN 1 ELSE 0 END) / COUNT(s.ComplaintID)) * 100, 2) as saved_percentage"),

                DB::raw("SUM(CASE WHEN s.ComplaintStatus = 2 THEN 1 ELSE 0 END) as solved_count"),
                DB::raw("ROUND((SUM(CASE WHEN s.ComplaintStatus = 2 THEN 1 ELSE 0 END) / COUNT(s.ComplaintID)) * 100, 2) as solved_percentage"),

                DB::raw("SUM(CASE WHEN s.fk_close_reason_id = 1 AND s.ComplaintStatus = 4 THEN 1 ELSE 0 END) as client_reason_count"),
                DB::raw("SUM(CASE WHEN s.fk_close_reason_id = 2 AND s.ComplaintStatus = 4 THEN 1 ELSE 0 END) as company_reason_count"),

            )

            ->groupBy(
                'complainttype.comtypeid',
                'complainttype.comtypename',

                'requesttype.requesttypeid',
                'requesttype.requesttypename'
            )
            ->orderBy('complainttype.comtypeid')
            ->get();

        return $data;
    }

    public function headings(): array
    {
        return [

            ' عدد الشكاوى بسبب الشركة ',
            ' عدد الشكاوى بسبب العميل ',
            ' نسبة الشكاوى المحفوظة  (%)',
            ' عدد الشكاوى المحفوظة ',
            ' نسبة الشكاوى المحلولة  (%)',
            ' عدد الشكاوى المحلولة ',
            ' نسبة الشكاوى من إجمالي الشكاوى (%)',
            'عدد الشكاوى  ',
            'نوع الطلب ',
            ' تصنيف الشكوى ',
        ];
    }

    public function map(mixed $row): array
    {
        return [

            $row->company_reason_count ?? 0,
            $row->client_reason_count ?? 0,

            $row->saved_percentage ?? 0,
            $row->saved_count ?? 0,

            $row->solved_percentage ?? 0,
            $row->solved_count ?? 0,

            $row->complaints_percentage ?? 0,
            $row->complaints_count ?? 0,
            $row->requesttypename ?? '',
            $row->comtypename ?? '',

        ];
    }
}
