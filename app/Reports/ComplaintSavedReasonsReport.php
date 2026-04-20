<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ComplaintSavedReasonsReport implements ReportInterface
{
    public function permission(): string
    {
        return 'view-report-complaint-saved-reasons-report';
    }

    public function label(): string
    {
        return ' تقرير بتفصيل أسباب حفظ الشكاوى الوارده ';
    }

    public function key(): string
    {
        return 'complaint-saved-reasons-report';
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


        $data = DB::table('comp_close_reason_classify as c')
            ->leftJoin('comp_close_reason as r', 'r.close_reason_ID', '=', 'c.fk_close_reason_id')
            ->leftJoin('sfdcomplaints as s', function ($join) use ($filters) {
                $join->on('s.fk_close_reason_classify_id', '=', 'c.close_reason_classify_id')
                    ->where('s.ComplaintStatus', 4);


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
                'c.close_reason_classify_id',
                'c.close_reason_classify_Name',
                'r.close_reason_ID',
                'r.close_reason_Name',
                DB::raw('COUNT(s.ComplaintID) as complaints_count')
            )


            ->groupBy(
                'c.close_reason_classify_id',
                'c.close_reason_classify_Name',
                'r.close_reason_ID',
                'r.close_reason_Name'
            )
            ->orderBy('r.close_reason_ID')
            ->orderBy('c.close_reason_classify_id')
            ->get();

        return $data;
    }



    public function headings(): array
    {
        return [

            'العدد ',
            'بسبب (عميل/جهاز) ',
            'التصنيف ',
        ];
    }

    public function map(mixed $row): array
    {
        return [

            $row->complaints_count ?? 0,
            $row->close_reason_Name ?? '',
            $row->close_reason_classify_Name ?? '',

        ];
    }
}
