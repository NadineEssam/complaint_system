<?php

namespace App\Reports;

use App\Models\RequestType;
use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ComplaintPercentageReport implements ReportInterface
{
    public function permission(): string
    {
        return 'reports.view-report-complaint-percentage-report';
    }

    public function label(): string
    {
        return ' تقرير بأعلى الطلبات الوارده من حيث التصنيف ';
    }

    public function key(): string
    {
        return 'complaint-percentage-report';
    }

    public function filters(): array
    {



        $request_type = RequestType::all()
            ->mapWithKeys(function ($item) {
                return [$item->requesttypeid  => $item->requesttypename];
            })
            ->toArray();
        $request_type[0] = 'الكل';



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





        ];
    }

    public function generate(array $filters): mixed
    {

        $totalComplaints = DB::table('sfdcomplaints as s')
            ->where('s.valid', 1)
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
            ->when(
                !empty($filters['request_type']) && $filters['request_type'] != 0,
                fn($q) =>
                $q->where('s.RequestType', '=', $filters['request_type'])
            )

            ->count();

        $data = DB::table('complainttype')
            ->leftJoin('requesttype', 'requesttype.requesttypeid', '=', 'complainttype.request_fk')
            ->leftJoin('sfdcomplaints as s', function ($join) use ($filters) {
                $join->on('s.ComplaintType', '=', 'complainttype.comtypeid');
                $join->where('s.valid', 1);



                if (!empty($filters['date_from'])) {
                    $join->whereDate('s.ComplaintDate', '>=', $filters['date_from']);
                }

                if (!empty($filters['date_to'])) {
                    $join->whereDate('s.ComplaintDate', '<=', $filters['date_to']);
                }
                if (!empty($filters['request_type']) && $filters['request_type'] != '0') {
                    $join->where('s.RequestType', $filters['request_type']);
                }
            })
            ->select(
                'complainttype.comtypeid',
                'complainttype.comtypename',
                'requesttype.requesttypeid',
                'requesttype.requesttypename',

                DB::raw('COUNT(CASE WHEN s.valid = 1  THEN 1 END) as complaints_count'),
                DB::raw("ROUND((COUNT(CASE WHEN s.valid = 1  THEN 1 END) / {$totalComplaints}) * 100, 2) as complaints_percentage"),


                DB::raw("SUM(CASE WHEN s.ComplaintStatus = 4 And s.valid = 1 THEN 1 ELSE 0 END) as saved_count"),
                DB::raw("ROUND((SUM(CASE WHEN s.ComplaintStatus = 4 AND s.valid = 1 THEN 1 ELSE 0 END) / COUNT(CASE WHEN s.valid = 1 THEN 1 END)) * 100, 2) as saved_percentage"),

                DB::raw("SUM(CASE WHEN s.ComplaintStatus = 2 AND s.valid = 1 THEN 1 ELSE 0 END) as solved_count"),
                DB::raw("ROUND((SUM(CASE WHEN s.ComplaintStatus = 2 AND s.valid = 1 THEN 1 ELSE 0 END) / COUNT(CASE WHEN s.valid = 1 THEN 1 END)) * 100, 2) as solved_percentage"),

                DB::raw("SUM(CASE WHEN s.fk_close_reason_id = 1 AND (s.ComplaintStatus = 4 OR s.ComplaintStatus = 2 ) AND s.valid = 1 THEN 1 ELSE 0 END) as client_reason_count"),
                DB::raw("SUM(CASE WHEN s.fk_close_reason_id = 2 AND (s.ComplaintStatus = 4 OR s.ComplaintStatus = 2 ) AND s.valid = 1 THEN 1 ELSE 0 END) as company_reason_count"),

            )

            ->groupBy(
                'complainttype.comtypeid',
                'complainttype.comtypename',

                'requesttype.requesttypeid',
                'requesttype.requesttypename'
            )
            ->orderBy('complainttype.comtypeid')
            ->get();


        /*
     * Add Total row
     */
        $totalCount = $data->sum('complaints_count');
        $savedCount = $data->sum('saved_count');
        $solvedCount = $data->sum('solved_count');
        $clientReasonCount = $data->sum('client_reason_count');
        $companyReasonCount = $data->sum('company_reason_count');

        $data->push((object) [
            'comtypeid' => null,
            'comtypename' => 'المجموع',

            'requesttypeid' => null,
            'requesttypename' => '-',

            'complaints_count' => $totalCount,

            'complaints_percentage' => $totalComplaints > 0
                ? round(($totalCount / $totalComplaints) * 100, 2)
                : 0,

            'saved_count' => $savedCount,

            'saved_percentage' => $totalCount > 0
                ? round(($savedCount / $totalCount) * 100, 2)
                : 0,

            'solved_count' => $solvedCount,

            'solved_percentage' => $totalCount > 0
                ? round(($solvedCount / $totalCount) * 100, 2)
                : 0,

            'client_reason_count' => $clientReasonCount,

            'company_reason_count' => $companyReasonCount,
        ]);


        return $data;
    }

    public function headings(): array
    {
        return [
            ' تصنيف البيان ',
            'نوع الطلب ',
            'عدد الطلبات',
            'نسبة الطلبات من إجمالي الطلبات (%)',
            'عدد الطلبات المحلولة',
            'نسبة الطلبات المحلولة (%)',
            'عدد الطلبات المحفوظة',
            'نسبة الطلبات المحفوظة (%)',
            'عدد الطلبات بسبب العميل',
            'عدد الطلبات بسبب الشركة',
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->comtypename ?? '',
            $row->requesttypename ?? '',
            $row->complaints_count ?? 0,
            $row->complaints_percentage ?? 0,
            $row->solved_count ?? 0,
            $row->solved_percentage ?? 0,
            $row->saved_count ?? 0,
            $row->saved_percentage ?? 0,
            $row->client_reason_count ?? 0,
            $row->company_reason_count ?? 0,
        ];
    }
}
