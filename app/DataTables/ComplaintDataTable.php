<?php

namespace App\DataTables;

use App\Models\Complaint;
use \Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ComplaintDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';
                if (PerUser('responses.index')) {
                    $html .= '
                         <a href="' . route('responses.index', ['complaint_id' => $model]) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="Edit complaint">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }
                // Edit Button
                if (PerUser('complaints.edit')) {
                    $html .= '
                        <a href="' . route('complaints.edit', ['complaint' => $model]) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="Edit complaint">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }

                // Delete Button
                if (PerUser('complaints.destroy')) {
                    $html .= '
                        <button 
                            class="btn btn-sm btn-outline-danger action-btn delete-this"
                            data-id="' . $model->id . '"
                            data-url="' . route('complaints.destroy', ['complaint' => $model]) . '"
                            data-bs-toggle="tooltip" 
                            title="Delete complaint">
                            <i class="bx bx-trash"></i>
                        </button>';
                }

                $html .= '</div>';

                return $html;
            })

            ->setRowId('ComplaintID')
        ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Spatie\Permission\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Complaint $model): QueryBuilder
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
            ->pageLength(10)
            ->parameters([
                'scrollX' => true,
            ])
            ->lengthMenu([10, 25, 50])
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

            Column::make('ComplaintID')->title('رقم الشكوي'),
            Column::make('ComplainerName')->title('اسم الشاكي'),

            Column::make('ComplaintNationalID')->title('الرقم القومي '),
            Column::make('ComplaintDate')->title('تاريخ الشكوي'),
            Column::computed('action')->title('الاجراءات')
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
