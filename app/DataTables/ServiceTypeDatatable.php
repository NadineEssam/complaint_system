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
            ->with('createdBy', 'updatedBy')
            ->select('srevicetyptid', 'srevicetyptname', 'created_by', 'updated_by', 'created_at', 'updated_at');
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($model) {
                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                if (PerUser('service-types.show')) {
                    $html .= '<a href="' . route('service.show', $model->srevicetyptid) . '" 
                        class="btn btn-sm btn-outline-info action-btn"
                        data-bs-toggle="tooltip" 
                        title="عرض">
                        <i class="bx bx-show"></i>
                    </a>';
                }

                if (PerUser('service.edit')) {
                    $html .= '<a href="' . route('service.edit', $model->srevicetyptid) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="تعديل">
                        <i class="bx bx-edit-alt"></i>
                    </a>';
                }

                if (PerUser('service.destroy')) {
                    $html .= '<button 
                        class="btn btn-sm btn-outline-danger action-btn delete-this"
                        data-id="' . $model->srevicetyptid . '"
                        data-url="' . route('service-types.destroy', $model->srevicetyptid) . '"
                        data-bs-toggle="tooltip" 
                        title="حذف">
                        <i class="bx bx-trash"></i>
                    </button>';
                }

                $html .= '</div>';
                return $html;
            })

            ->editColumn('created_by', function ($row) {
                return $row->createdBy?->name ?? '-';
            })

            ->editColumn('updated_by', function ($row) {
                return $row->updatedBy?->name ?? '-';
            })

            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '-';
            })

            ->editColumn('updated_at', function ($row) {
                return $row->updated_at ? $row->updated_at->format('Y-m-d H:i') : '-';
            })

            ->rawColumns(['action'])
            ->setRowId('srevicetyptid');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service_types_table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->pageLength(10)
            ->lengthMenu([10, 25, 50]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('srevicetyptid')->title('الكود'),
            Column::make('srevicetyptname')->title('اسم الخدمة'),
            Column::make('created_by')->title('أضيف بواسطة'),
            Column::make('updated_by')->title('عدل بواسطة'),
            Column::make('created_at')->title('تاريخ الإنشاء'),
            Column::make('updated_at')->title('تاريخ التعديل'),

            Column::computed('action')
                ->title('الإجراءات')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ServiceTypes_' . date('YmdHis');
    }
}