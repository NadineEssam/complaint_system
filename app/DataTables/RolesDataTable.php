<?php

namespace App\DataTables;

use \Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->where('guard_name', 'web')))

            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                // Edit Button
                if (PerUser('roles.edit')) {
                    $html .= '
                        <a href="' . route('roles.edit', ['role' => $model]) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="Edit Role">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }

                // Delete Button
                if (PerUser('roles.destroy')) {
                    $html .= '
                        <button 
                            class="btn btn-sm btn-outline-danger action-btn delete-this"
                            data-id="' . $model->id . '"
                            data-url="' . route('roles.destroy', ['role' => $model]) . '"
                            data-bs-toggle="tooltip" 
                            title="Delete Role">
                            <i class="bx bx-trash"></i>
                        </button>';
                }

                $html .= '</div>';

                return $html;
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format('H:i:s Y-m-d');
            })
            ->setRowId('id')
        ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Spatie\Permission\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('roles')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //                    ->dom('Bfrtip')
            ->orderBy(1)
            ->pageLength(100)
            ->parameters([
                'scrollX' => true,
            ])
            ->lengthMenu([100, 300, 500])
            //                    ->buttons(
            //                        Button::make('export'),
            //                        Button::make('print'),
            //                        Button::make('reset'),
            //                        Button::make('reload')
            //                    )
        ;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('name'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Roles_' . date('YmdHis');
    }
}
