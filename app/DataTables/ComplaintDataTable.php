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
        return (new EloquentDataTable(
            $query->select(
                'sfdcomplaints.*',
                'compstatus.statusText as status_name',
                'complainttype.comtypename as complaint_type',
                'requesttype.requesttypename as requesttypename'
                // 'users_groups.userID as created_by_name',
                // 'updated_by.userID as updated_by_name'
            )
                ->leftJoin('compstatus', 'sfdcomplaints.ComplaintStatus', '=', 'compstatus.statusID')
                ->leftJoin('complainttype', 'sfdcomplaints.ComplaintType', '=', 'complainttype.comtypeid')
                ->leftJoin('requesttype', 'sfdcomplaints.RequestType', '=', 'requesttype.requesttypeid')
            // ->leftJoin('users_groups', 'sfdcomplaints.created_by', '=', 'users_groups.ID')
            // ->leftJoin('users_groups as updated_by', 'sfdcomplaints.updated_by', '=', 'updated_by.ID')



        ))

            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';
                // Add this to the action column (optional - you can also click the ID)
                if (PerUser('complaints.show')) {
                    $html .= '
                                <a href="' . route('complaints.show', ['complaint' => $model]) . '" 
                                class="btn btn-sm btn-outline-info action-btn"
                                data-bs-toggle="tooltip" 
                                title="عرض الشكوى">
                                    <i class="bx bx-show"></i>
                                </a>';
                }
                if (PerUser('responses.index')) {
                    $html .= '
                         <a href="' . route('responses.index', ['complaint_id' => $model]) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="الرد على البيان">
                            <i class="fas fa-reply-all"></i>
                        </a>';
                }
                // Edit Button
                if (PerUser('complaints.edit')) {
                    $html .= '
                        <a href="' . route('complaints.edit', ['complaint' => $model]) . '" 
                        class="btn btn-sm btn-outline-primary action-btn"
                        data-bs-toggle="tooltip" 
                        title="تعديل الشكوى">
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
                            title="إزاله الشكوى">
                            <i class="bx bx-trash"></i>
                        </button>';
                }

                $html .= '</div>';

                return $html;
            })
            // ->editColumn('status_name', function ($model) {
            //     return $model->status_name ?? 'غير محدد';
            // })

            ->setRowId('ComplaintID')
        ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Spatie\Permission\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function query(Complaint $model): QueryBuilder
    // {
    //     return $model->newQuery();
    // }

    // public function query(Complaint $model): QueryBuilder
    // {
    //     $query = $model->newQuery();

    //     if ($gender = $this->request->get('gender_filter')) {
    //         $query->where('ComplainerGender', $gender);
    //     }

    //     if ($gov = $this->request->get('gov_filter')) {
    //         $query->where('ComplaintGovernorate', $gov);
    //     }

    //     if ($status = $this->request->get('status_filter')) {
    //         $query->where('ComplaintStatus', $status);
    //     }

    //     if ($reqtype = $this->request->get('reqtype_filter')) {
    //         $query->where('RequestType', $reqtype);
    //     }

    //     return $query;
    // }

    public function query(Complaint $model): QueryBuilder
    {
        $query = $model->newQuery();

        // عرض شكاوى عام 2022 فقط
        // $query->whereYear('ComplaintDate', 2022);
        $query->whereYear('ComplaintDate', '>=', 2022);
        if ($gender = $this->request->get('gender_filter')) {
            $query->where('ComplainerGender', $gender);
        }

        if ($gov = $this->request->get('gov_filter')) {
            $query->where('ComplaintGovernorate', $gov);
        }

        if ($status = $this->request->get('status_filter')) {
            $query->where('ComplaintStatus', $status);
        }

        if ($reqtype = $this->request->get('reqtype_filter')) {
            $query->where('RequestType', $reqtype);
        }

        return $query;
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
            ->orderBy(0)
            ->pageLength(10)
            ->parameters([
                // 'scrollX' => true,
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
            Column::make('requesttypename')
                ->name('requesttype.requesttypename')
                ->title('نوع الشكوى'),
            Column::make('complaint_type')
                ->name('complainttype.comtypename')
                ->title('تصنيف الشكوى'),
            Column::make('ComplainerName')->title('اسم الشاكي'),

            Column::make('ComplaintNationalID')->title('الرقم القومي '),
            Column::make('ComplainerPhone')->title('رقم الهاتف المحمول '),


            Column::make('status_name')
                ->name('compstatus.statusText')
                ->title('الحالة'),



            Column::make('ComplaintDate')->title('تاريخ الشكوي'),

            Column::make('username')
                ->title('موظف الشكوي'),

            Column::make('UpdateUser')

                ->title('موظف التعديل'),



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
