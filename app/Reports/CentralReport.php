<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CentralReport implements ReportInterface
{
    public function permission(): string
    {
        return 'reports.view-report-central-report';
    }

    public function label(): string
    {
        return ' تقرير مركزى لجميع الشكاوى والاستفسارات الوارده ';
    }

    public function key(): string
    {
        return 'central-report';
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


        $request_status = DB::table('compstatus')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->statusID  => $item->statusText];
            })
            ->toArray();
        $request_status[0] = 'الكل';

        $request_source = DB::table('comsources')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->comsourcesid  => $item->comsourcesname];
            })
            ->toArray();
        $request_source[0] = 'الكل';

        $request_office = DB::table('office')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->ID  => $item->REG_OFFIC_NAMA];
            })
            ->toArray();
        $request_office[0] = 'الكل';



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
                [
                    'name'        => 'request_office',
                    'label'       => 'المكتب',
                    'type'        => 'select',
                    'options'     =>  $request_office,
                    'required'    => false,
                    'default'     => '0',
                ],

        ];
    }

    public function generate(array $filters): mixed
    {

        $data = DB::table('sfdcomplaints')
            ->leftJoin('office', 'office.id', '=', 'sfdcomplaints.office')
            ->leftJoin('comsources', 'comsources.comsourcesid', '=', 'sfdcomplaints.ComplaintSources')
            ->leftJoin('requesttype', 'requesttype.requesttypeid', '=', 'sfdcomplaints.RequestType')
            ->leftJoin('compstatus', 'compstatus.statusID', '=', 'sfdcomplaints.ComplaintStatus')
            ->leftJoin('comp_close_reason', 'comp_close_reason.close_reason_ID', '=', 'sfdcomplaints.fk_close_reason_id')
            ->select(
                'sfdcomplaints.ComplainerName',
                'sfdcomplaints.ComplainerPhone',
                'sfdcomplaints.ComplaintDate',
                'office.REG_OFFIC_NAMA',
                'comsources.comsourcesname',
                'requesttype.requesttypename',
                'sfdcomplaints.ComplaintText',
                'sfdcomplaints.Comment',
                'sfdcomplaints.statusdetails',
                'compstatus.statusText',
                'comp_close_reason.close_reason_Name'
            )

            ->when($filters['date_from'] ?? null, function ($query, $date_from) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '>=', $date_from);
            })
            ->when($filters['date_to'] ?? null, function ($query, $date_to) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '<=', $date_to );
            })
            ->when($filters['request_type'] ?? null, function ($query, $request_type) {
                if ($request_type != '0') {
                    $query->where('sfdcomplaints.RequestType', $request_type);
                }
            })
            ->when($filters['request_status'] ?? null, function ($query, $request_status) {
                if ($request_status != '0') {
                    $query->where('sfdcomplaints.ComplaintStatus', $request_status);
                }
            })
            ->when($filters['request_source'] ?? null, function ($query, $request_source) {
                if ($request_source != '0') {
                    $query->where('sfdcomplaints.ComplaintSources', $request_source);
                }
            })
            ->when($filters['request_office'] ?? null, function ($query, $request_office) {
                if ($request_office != '0') {
                    $query->where('sfdcomplaints.office', $request_office);
                }
            })

            ->get();

        return $data;
    }



    public function headings(): array
    {
        return [
            'تاريخ الشكوى',
            'نص الشكوى',
            'التعليق',
            'تفاصيل الحالة',
            'سبب الحفظ',
            'حالة الطلب',
            'نوع الطلب',
            'المصدر ',
            ' المكتب ',
            'هاتف المشتكي',
            'اسم المشتكي',
        ];
    }

    public function map(mixed $row): array
    {
        return [

            $row->ComplaintDate,
            $row->ComplaintText,
            $row->Comment,
            $row->statusdetails,
            $row->close_reason_Name ?? '',
            $row->statusText,
            $row->requesttypename,
            $row->comsourcesname,
            $row->REG_OFFIC_NAMA,
            $row->ComplainerPhone,
            $row->ComplainerName,

        ];
    }
}
