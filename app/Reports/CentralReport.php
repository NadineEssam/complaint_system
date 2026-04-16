<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CentralReport implements ReportInterface
{
    public function permission(): string
    {
        return 'view-report-central-report';
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
        return [
            [
                'name'        => 'date_from',
                'label'       => 'من تاريخ',
                'type'        => 'date',
                'required'    => false,
            ],
            [
                'name'        => 'date_to',
                'label'       => 'إلى تاريخ',
                'type'        => 'date',
                'required'    => false,
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
    ->select(
        'sfdcomplaints.ComplainerName',
        'office.REG_OFFIC_NAMA',
        'comsources.comsourcesname',
        'requesttype.requesttypename',
        'sfdcomplaints.ComplaintText',
        'sfdcomplaints.Comment',
        'sfdcomplaints.statusdetails',
        'compstatus.statusText'
    )
   
            ->when($filters['date_from'] ?? null, function ($query, $date_from) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '>=', $date_from);
            })
            ->when($filters['date_to'] ?? null, function ($query, $date_to) {
                $query->whereDate('sfdcomplaints.ComplaintDate', '<=', $date_to);
            })

            ->get();

        return $data;
    }



    public function headings(): array
    {
        return ['عدد الاستفسارات', 'عدد الشكاوى', 'إجمالي العدد',  'اسم المصدر'];
    }

    public function map(mixed $row): array
    {
        return [

            $row->inquiry_count,
            $row->complaint_count,
            $row->total_count,
            $row->comsourcesname ?? 'غير محدد',

        ];
    }
}
