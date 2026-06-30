<?php

namespace App\DataTables;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ComplaintDuplicatesDataTable extends DataTable
{
    /**
     * The root (original) complaint whose family (itself + children)
     * is being listed. Set via forComplaint() before render().
     *
     * @var \App\Models\Complaint|null
     */
    protected ?Complaint $rootComplaint = null;

    /**
     * Bind the root complaint this table should list duplicates for.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return $this
     */
    public function forComplaint(Complaint $complaint): self
    {
        $this->rootComplaint = $complaint;

        return $this;
    }

    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rootId = $this->rootComplaint->ComplaintID;

        // Children ordered by ComplaintID (creation order) so we can map
        // each child's id to a stable "تكرار رقم N" sequence number,
        // independent of whatever order/paging the datatable itself uses.
        $childIdsInOrder = Complaint::where('parent_id', $rootId)
            ->orderBy('ComplaintID')
            ->pluck('ComplaintID')
            ->values();

        $sequenceByChildId = $childIdsInOrder
            ->mapWithKeys(fn ($id, $index) => [$id => $index + 1]);

        return (new EloquentDataTable(
            $query->select(
                'sfdcomplaints.*',
                'compstatus.statusText as status_name',
                'requesttype.requesttypename as requesttypename'
            )
                ->leftJoin('compstatus', 'sfdcomplaints.ComplaintStatus', '=', 'compstatus.statusID')
                ->leftJoin('requesttype', 'sfdcomplaints.RequestType', '=', 'requesttype.requesttypeid')
        ))
            ->addColumn('duplicate_badge', function ($model) use ($rootId, $sequenceByChildId) {

                if ($model->ComplaintID == $rootId) {
                    return '<span class="badge bg-primary">الأصل</span>';
                }

                $sequence = $sequenceByChildId->get($model->ComplaintID, '?');

                return '<span class="badge bg-secondary">تكرار رقم ' . $sequence . '</span>';
            })
            ->addColumn('action', function ($model) {

                $html = '<div class="d-flex align-items-center gap-2 justify-content-end">';

                if (PerUser('complaints.show')) {
                    $html .= '
                                <a href="' . route('complaints.show', ['complaint' => $model]) . '"
                                class="btn btn-sm btn-outline-info action-btn"
                                data-bs-toggle="tooltip"
                                title="عرض البيان">
                                    <i class="bx bx-show"></i>
                                </a>';
                }

                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['duplicate_badge', 'action'])
            ->setRowId('ComplaintID');
    }

    /**
     * Get query source of dataTable: the root complaint itself plus
     * every child whose parent_id points back to it, oldest first so
     * "تكرار رقم 1" really is the first duplicate created.
     *
     * @param \App\Models\Complaint $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Complaint $model): QueryBuilder
{
    if (!$this->rootComplaint) {
        abort(500, 'Root complaint not set. Call forComplaint() first.');
    }

    $rootId = $this->rootComplaint->ComplaintID;

    return $model->newQuery()
        ->where(function ($q) use ($rootId) {
            $q->where('sfdcomplaints.ComplaintID', $rootId)
              ->orWhere('sfdcomplaints.parent_id', $rootId);
        })
        ->orderByRaw('CASE WHEN sfdcomplaints.parent_id IS NULL THEN 0 ELSE 1 END')
        ->orderBy('sfdcomplaints.ComplaintID');
}

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('complaint-duplicates-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->pageLength(10)
            ->lengthMenu([10, 25, 50]);
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
            Column::computed('duplicate_badge')->title('النوع'),
            Column::make('requesttypename')
                ->name('requesttype.requesttypename')
                ->title('نوع الطلب'),
            Column::make('status_name')
                ->name('compstatus.statusText')
                ->title('الحالة'),
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
        return 'ComplaintDuplicates_' . date('YmdHis');
    }
}