<?php

namespace App\DataTables;

use App\Models\ComplaintResponse;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ComplaintResponseDataTable extends DataTable
{
    protected $complaintId;

    public function withComplaint($id)
    {
        $this->complaintId = $id;
        return $this;
    }

    public function query(ComplaintResponse $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('complaint_id', $this->complaintId);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                if (PerUser('responses.edit')) {
                    $html .= '
                        <a href="' . route('responses.edit', $model->id) . '" 
                        class="btn btn-sm btn-outline-primary action-btn">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }

                if (PerUser('responses.destroy')) {
                    $html .= '
                        <button 
                            class="btn btn-sm btn-outline-danger action-btn delete-this"
                            data-id="' . $model->id . '"
                            data-url="' . route('responses.destroy', $model->id) . '"
                            "data-bs-toggle="tooltip" 
                            title="Delete complaint">
                            <i class="bx bx-trash"></i>
                        </button>';

                
                }

                $html .= '</div>';

                return $html;
            })

            ->editColumn('ComplaintStatus', function ($row) {
                return $row->status->statusText ?? '-';
            })

            ->editColumn('ComplaintService', function ($row) {
                return $row->serviceType->srevicetyptname ?? '-';
            })

            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('Y-m-d H:i')
                    : '-';
            })

            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('complaint_responses') // ❗ كان غلط "roles"
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->pageLength(10)
            ->parameters([
                'scrollX' => true,
            ])
            ->lengthMenu([10, 25, 50]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id'),

            Column::make('ComplaintStatus')->title('الحالة'),
            Column::make('ComplaintService')->title('نوع الخدمة'),
            Column::make('ComplaintText')->title('الرد'),
            Column::make('created_at')->title('تاريخ الرد'),

            Column::computed('action')
                ->title('الإجراءات')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ComplaintResponses_' . date('YmdHis');
    }
}