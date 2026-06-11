<?php

namespace App\DataTables;

use App\Models\CompCloseReasonClassify;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompCloseReasonClassifyDataTable extends DataTable
{
    public function query(CompCloseReasonClassify $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['closeReason', 'createdBy', 'updatedBy']);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                if (PerUser('close-reason-classify.show')) {
                    $html .= ' <a href="' . route('close-reason-classify.show', $model->close_reason_classify_id) . '" 
                            class="btn btn-sm btn-outline-info action-btn"
                            data-bs-toggle="tooltip" 
                            title="عرض التصنيف">
                                <i class="bx bx-show"></i>
                            </a>';
                }

                if (PerUser('close-reason-classify.edit')) {
                    $html .= '
                        <a href="' . route('close-reason-classify.edit', $model->close_reason_classify_id) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="تعديل التصنيف">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }

                if (PerUser('close-reason-classify.destroy')) {
                    $html .= '
                        <button 
                            class="btn btn-sm btn-outline-danger action-btn delete-this"
                            data-id="' . $model->close_reason_classify_id . '"
                            data-url="' . route('close-reason-classify.destroy', $model->close_reason_classify_id) . '"
                            data-bs-toggle="tooltip" 
                            title="حذف التصنيف">
                            <i class="bx bx-trash"></i>
                        </button>';
                }

                $html .= '</div>';

                return $html;
            })

            ->editColumn('closeReason.close_reason_Name', function ($row) {
                return $row->closeReason->close_reason_Name ?? '-';
            })

            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('Y-m-d H:i')
                    : '-';
            })

            ->editColumn('createdBy.userID', function ($row) {
                return $row->createdBy->userID ?? '-';
            })

            ->rawColumns(['action'])
            ->setRowId('close_reason_classify_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('close_reason_classify')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->pageLength(10)
            ->parameters([
            ])
            ->lengthMenu([10, 25, 50]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('close_reason_classify_id')->title('الرقم'),
            Column::make('close_reason_classify_Name')->title('اسم التصنيف'),
            Column::make('closeReason.close_reason_Name')->title('السبب الرئيسي'),
            Column::make('created_at')->title('تاريخ الإنشاء'),
            Column::make('createdBy.userID')->title('أنشأ بواسطة'),

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
        return 'CompCloseReasonClassify_' . date('YmdHis');
    }
}