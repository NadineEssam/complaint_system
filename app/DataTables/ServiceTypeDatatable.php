<?php

namespace App\DataTables;

use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ServiceTypeDataTable extends DataTable
{
    public function query(ServiceType $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['createdBy', 'updatedBy']);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                if (PerUser('services.show')) {
                    $html .= ' <a href="' . route('services.show', $model->srevicetyptid) . '" 
                            class="btn btn-sm btn-outline-info action-btn"
                            data-bs-toggle="tooltip" 
                            title="عرض الخدمة">
                                <i class="bx bx-show"></i>
                            </a>';
                }

                if (PerUser('services.edit')) {
                    $html .= '
                        <a href="' . route('services.edit', $model->srevicetyptid) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="تعديل الخدمة">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }

                if (PerUser('services.destroy')) {
                    $html .= '
                        <button 
                            class="btn btn-sm btn-outline-danger action-btn delete-this"
                            data-id="' . $model->srevicetyptid . '"
                            data-url="' . route('services.destroy', $model->srevicetyptid) . '"
                            data-bs-toggle="tooltip" 
                            title="حذف الخدمة">
                            <i class="bx bx-trash"></i>
                        </button>';
                }

                $html .= '</div>';

                return $html;
            })
            ->editColumn('validity', function ($row) {
                return $row->validity == 1
                    ? '<span class="badge bg-success">فعّال</span>'
                    : '<span class="badge bg-danger">غير فعّال</span>';
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
            ->rawColumns(['action', 'validity'])
            ->setRowId('srevicetyptid');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service_types')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->pageLength(10)
            ->parameters([])
            ->lengthMenu([10, 25, 50]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('srevicetyptid')->title('الرقم'),
            Column::make('srevicetyptname')->title('اسم الخدمة'),
            Column::make('validity')->title('الحالة'),
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
        return 'ServiceTypes_' . date('YmdHis');
    }
}
