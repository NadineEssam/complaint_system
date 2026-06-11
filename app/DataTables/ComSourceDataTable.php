<?php

namespace App\DataTables;

use App\Models\ComSource;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ComSourceDataTable extends DataTable
{
    public function query(ComSource $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['createdBy', 'updatedBy']);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                if (PerUser('sources.show')) {
                    $html .= ' <a href="' . route('sources.show', $model->comsourcesid) . '" 
                            class="btn btn-sm btn-outline-info action-btn"
                            data-bs-toggle="tooltip" 
                            title="عرض المصدر">
                                <i class="bx bx-show"></i>
                            </a>';
                }

                if (PerUser('sources.edit')) {
                    $html .= '
                        <a href="' . route('sources.edit', $model->comsourcesid) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="تعديل المصدر">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }

                if (PerUser('sources.destroy')) {
                    $html .= '
                        <button 
                            class="btn btn-sm btn-outline-danger action-btn delete-this"
                            data-id="' . $model->comsourcesid . '"
                            data-url="' . route('sources.destroy', $model->comsourcesid) . '"
                            data-bs-toggle="tooltip" 
                            title="حذف المصدر">
                            <i class="bx bx-trash"></i>
                        </button>';
                }

                $html .= '</div>';

                return $html;
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
            ->setRowId('comsourcesid');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('com_sources')
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
            Column::make('comsourcesid')->title('الرقم'),
            Column::make('comsourcesname')->title('اسم المصدر'),
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
        return 'ComSources_' . date('YmdHis');
    }
}