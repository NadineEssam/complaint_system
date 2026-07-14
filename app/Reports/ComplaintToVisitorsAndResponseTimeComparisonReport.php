<?php

namespace App\Reports;

use App\Models\CompStatus;
use App\Models\ComSource;
use App\Models\RequestType;
use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ComplaintToVisitorsAndResponseTimeComparisonReport implements ReportInterface
{
    public function permission(): string
    {
        return 'reports.view-report-complaint-to-visitors-and-response-time-comparison-report';
    }

    public function label(): string
    {
        return ' تقرير مقارنة مؤشرات الشكاوى والمترددين على المكاتب وزمن الاستجابة';
    }

    public function key(): string
    {
        return 'complaint-to-visitors-and-response-time-comparison-report';
    }

    public function filters(): array
    {


        $request_status = CompStatus::all()
            ->mapWithKeys(function ($item) {
                return [$item->statusID  => $item->statusText];
            })
            ->toArray();
        $request_status[0] = 'الكل';

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
                'name'        => 'request_status',
                'label'       => 'حالة الطلب',
                'type'        => 'select',
                'options'     =>  $request_status,
                'required'    => false,
                'default'     => '0',
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


        $topReason = DB::table('sfdcomplaints as c')
            ->select(
                'office',
                'fk_close_reason_classify_id',
                DB::raw('COUNT(*) as classify_count'),
                DB::raw('ROW_NUMBER() OVER (
            PARTITION BY office
            ORDER BY COUNT(*) DESC
        ) as rn')
            )
            ->where('valid', 1)
            ->when(!empty($filters['date_from']), function ($q) use ($filters) {
                $q->where('c.ComplaintDate', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function ($q) use ($filters) {
                $q->where('c.ComplaintDate', '<=', $filters['date_to']);
            })
            ->when(!empty($filters['request_status']) && $filters['request_status'] != '0', function ($q) use ($filters) {
                $q->where('c.ComplaintStatus', $filters['request_status']);
            })
            ->when(!empty($filters['request_type']) && $filters['request_type'] != '0', function ($q) use ($filters) {
                $q->where('c.RequestType', $filters['request_type']);
            })
            ->groupBy('office', 'fk_close_reason_classify_id');

        $firstResponses = DB::table('complaint_responses')
            ->select(
                'complaint_id',
                DB::raw('MIN(created_at) as first_response_at')
            )
            ->whereIn('complaintStatus', [2, 4])
            ->groupBy('complaint_id');

        $data = DB::table('ben.OFFICE as o')
            ->leftJoin('sfdcomplaints as c', function ($join) use ($filters) {

                $join->on('o.id', '=', 'c.office');
                $join->where('c.valid', 1);
                if (!empty($filters['date_from'])) {
                    $join->where('c.ComplaintDate', '>=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $join->where('c.ComplaintDate', '<=', $filters['date_to']);
                }
                if (!empty($filters['request_status']) && $filters['request_status'] != '0') {
                    $join->where('c.ComplaintStatus', $filters['request_status']);
                }
                if (!empty($filters['request_type']) && $filters['request_type'] != '0') {
                    $join->where('c.RequestType', $filters['request_type']);
                }
            })
            ->leftJoinSub($topReason, 'tr', function ($join) {
                $join->on('o.id', '=', 'tr.office')
                    ->where('tr.rn', 1);
            })

            ->leftJoin(
                'comp_close_reason_classify as crc',
                'tr.fk_close_reason_classify_id',
                '=',
                'crc.close_reason_classify_id'
            )


            ->select(

                'o.id as office',
                'o.REG_OFFIC_NAMA as office_name',
                'crc.close_reason_classify_name as classify_name',
                'tr.classify_count',

                DB::raw('COUNT(c.complaintID) as total_complaints'),

                DB::raw('SUM(CASE WHEN c.ComplaintStatus = 1 THEN 1 ELSE 0 END) as follow_up_count'),
                DB::raw('SUM(CASE WHEN c.ComplaintStatus = 2 THEN 1 ELSE 0 END) as solved_count'),
                DB::raw('SUM(CASE WHEN c.ComplaintStatus = 3 THEN 1 ELSE 0 END) as new_count'),
                DB::raw('SUM(CASE WHEN c.ComplaintStatus = 4 THEN 1 ELSE 0 END) as saved_count')

            )
            ->groupBy('o.id', 'o.REG_OFFIC_NAMA' , 'crc.close_reason_classify_name', 'tr.classify_count')
            ->get()
            ->keyBy('office');


        $responseRows = DB::table('sfdcomplaints as c')

            ->leftJoinSub($firstResponses, 'fr', function ($join) {

                $join->on('c.complaintID', '=', 'fr.complaint_id');
            })
            ->select(
                'c.office',
                'c.ComplaintDate',
                'c.complaintID',
                'fr.first_response_at'
            )
            ->where('c.valid', 1)
            ->when(!empty($filters['date_from']), function ($q) use ($filters) {
                $q->where('c.ComplaintDate', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function ($q) use ($filters) {
                $q->where('c.ComplaintDate', '<=', $filters['date_to']);
            })
            ->when(!empty($filters['request_status']) && $filters['request_status'] != '0', function ($q) use ($filters) {
                $q->where('c.ComplaintStatus', $filters['request_status']);
            })
            ->when(!empty($filters['request_type']) && $filters['request_type'] != '0', function ($q) use ($filters) {
                $q->where('c.RequestType', $filters['request_type']);
            })
            ->get();


        foreach ($responseRows as $row) {

            if (!$row->first_response_at) {
                continue;
            }

            if (!isset($data[$row->office])) {
                continue;
            }

            $days = $this->workingDaysWithFractions(
                $row->ComplaintDate,
                $row->first_response_at
            );

            if (!isset($data[$row->office]->total_response_days)) {
                $data[$row->office]->total_response_days = 0;
                $data[$row->office]->responded_count = 0;
            }

            // if ($row->office == 1) {
            //     dump([
            //         'office' => $row->office,
            //         'complaint_id' => $row->complaintID,
            //         'complaint_date' => $row->ComplaintDate,
            //         'first_response' => $row->first_response_at,
            //         'days' => $days,
            //     ]);
            // }

            $data[$row->office]->total_response_days += $days;
            $data[$row->office]->responded_count++;
        }

        foreach ($data as $row) {

            $row->total_response_days = round($row->total_response_days ?? 0, 2);

            $row->responded_count = $row->responded_count ?? 0;

            $row->average_response_days =
                $row->responded_count != 0
                ? round(
                    $row->total_response_days /
                        $row->responded_count,
                    2
                )
                : 0;

            $row->perfect_response = $row->average_response_days != 0 ? (1.5 / $row->average_response_days) * 100 : 0;
            $row->perfect_response = round($row->perfect_response, 2);
        }

       // dd($data);
        return $data;
    }



    public function headings(): array
    {
        return [

            'المكتب ',
            'المترددين على المكتب',
            'عدد الطلبات الجديدة',
            'عدد الطلبات قيد المتابعة',
            'عدد الطلبات المحفوظة',
            'عدد الطلبات المحلولة',
            'إجمالي عدد الطلبات',
            'نسبه الشكاوي بالنسبه المترددين على المكتب',
            'أكثر تصنيف للطلبات',
            'عدد الطلبات لهذا التصنيف',
            'متوسط زمن الاستجابة في اليوم',
            'نسبه زمن الاستجابة  للزمن المثالي (1.5 يوم) %',
        ];
    }

    public function map(mixed $row): array
    {
        return [

            $row->office_name ,
            "المترددين على المكتب" ,
            $row->new_count ,
            $row->follow_up_count ,
            $row->saved_count ,
            $row->solved_count ,
            $row->total_complaints ,
            "نسبه الشكاوي بالنسبه المترددين على المكتب" ,
            $row->classify_name ?? "غير محدد",
            $row->classify_count  ,
            $row->average_response_days,
            $row->perfect_response ,
          

        ];
    }





    function workingDaysWithFractions($start, $end)
    {
        $start = Carbon::parse($start);

        $end = Carbon::parse($end);

        if ($start->gte($end)) {
            return 0;
        }

        $seconds = 0;

        $current = $start->copy();

        while ($current->lt($end)) {

            $next = $current->copy()->startOfDay()->addDay();

            if ($next->gt($end)) {
                $next = $end->copy();
            }

            if (!in_array($current->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY])) {

                $seconds += $current->diffInSeconds($next);
            }

            $current = $next;
        }

        return round($seconds / 86400, 2);
    }
}
