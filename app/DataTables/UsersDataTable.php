<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
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
            ->editColumn('checkbox',function($user){
                return '<div class="custom-control custom-checkbox"><input type="checkbox"  class="custom-control-input user-checkbox" value="'.$user->id.'" id="selectCheckbox-'.$user->id.'"><label class="custom-control-label" for="selectCheckbox-'.$user->id.'"></label></div>';
            })
                ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                // Edit Button
                if (PerUser('users.edit')) {
                    $html .= '
                        <a href="' . route('users.edit', ['user' => $model]) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="Edit user">
                            <i class="bx bx-edit-alt"></i>
                        </a>';
                }

                // Delete Button
                if (PerUser('users.destroy')) {
                    $html .= '
                        <button 
                            class="btn btn-sm btn-outline-danger action-btn delete-this"
                            data-id="' . $model->id . '"
                            data-url="' . route('users.destroy', ['user' => $model]) . '"
                            data-bs-toggle="tooltip" 
                            title="Delete user">
                            <i class="bx bx-trash"></i>
                        </button>';
                }

                $html .= '</div>';

                return $html;
            })
      
        ->addColumn('roles', function ($model) {
            $roles = $model->getRoleNames()->map(function ($role) {
                return '<span class="badge bg-info text-light p-2">' . $role . '</span>';
            })->implode(' ');
            return $roles ?: '<span class="text-muted"> - </span>';
        })
                // ->editColumn('created_at',function ($model){
                //     return $model->created_at?->format('Y-m-d H:i:s');
                // })

            ->rawColumns(['checkbox','action','roles'])
            ->setRowId('userID');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
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
                    ->setTableId('users')
            ->columns($this->getColumns())
            ->minifiedAjax()
//                    ->dom('Bfrtip')
            ->orderBy(1)
            ->pageLength(10)
            ->lengthMenu([10, 25 ,50])
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
            // Column::make('checkbox')
            //     ->title('<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="selectAllCheckbox"><label class="custom-control-label" for="selectAllCheckbox"></label></div>')
            //     ->searchable(false)
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width('10px')
            //     ->orderable(false),
            Column::make('ID')->title('رقم المستخدم'),
            Column::make('userID')->title('اسم المستخدم'),
            Column::make('roles')->title('الأدوار و الصلاحيات')->orderable(false)->searchable(false),
          
            Column::computed('action')->title('الإجراءات')
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
        return 'Users_' . date('YmdHis');
    }
}
