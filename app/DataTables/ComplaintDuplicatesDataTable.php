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
    protected ?Complaint $rootComplaint = null;
    protected ?int $highlightId = null;

    public function forComplaint(Complaint $complaint): self
    {
        $this->rootComplaint = $complaint;

        return $this;
    }

    public function highlight(int $complaintId): self
    {
        $this->highlightId = $complaintId;

        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rootId = $this->rootComplaint->ComplaintID;

        // Whole family tree (root + every descendant at any depth),
        // computed once and used to build nested labels/parent links
        // regardless of how the table itself is paginated/sorted.
        $familyRows = Complaint::descendantsOf($rootId);

        [$labels, $parentIds] = $this->buildLabels($rootId, $familyRows);

        return (new EloquentDataTable(
            $query->select(
                'sfdcomplaints.*',
                'compstatus.statusText as status_name',
                'requesttype.requesttypename as requesttypename'
            )
                ->leftJoin('compstatus', 'sfdcomplaints.ComplaintStatus', '=', 'compstatus.statusID')
                ->leftJoin('requesttype', 'sfdcomplaints.RequestType', '=', 'requesttype.requesttypeid')
        ))
            ->addColumn('duplicate_badge', function ($model) use ($rootId, $labels) {

                if ($model->ComplaintID == $rootId) {
                    return '<span class="badge bg-primary">الأصل</span>';
                }

                $label = $labels[$model->ComplaintID] ?? '?';
                $depth = substr_count($label, '.'); // 0 for top-level dup, 1+ for nested
                $indent = $depth * 18;

                return '<span class="badge bg-secondary" style="margin-right:' . $indent . 'px;">'
                    . 'تكرار رقم ' . $label
                    . '</span>';
            })
            ->addColumn('parent_complaint', function ($model) use ($rootId, $parentIds) {

                $parentId = $parentIds[$model->ComplaintID] ?? null;

                if ($model->ComplaintID == $rootId || $parentId === null) {
                    return '<span class="text-muted">-</span>';
                }

                return '<span class="badge bg-light text-dark border">' . $parentId . '#</span>';
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
            ->rawColumns(['duplicate_badge', 'parent_complaint', 'action'])
            ->setRowId('ComplaintID')
            ->setRowClass(function ($model) {
                return $model->ComplaintID == $this->highlightId ? 'current-complaint-row' : '';
            });
    }

    /**
     * Assign hierarchical labels (1, 1.1, 1.1.1...) and direct-parent
     * IDs to every complaint in the family tree, based on sibling
     * order (ComplaintID ascending) within each parent group.
     *
     * @param  int  $rootId
     * @param  \Illuminate\Support\Collection  $familyRows  All descendants of root (any depth).
     * @return array{0: array<int,string>, 1: array<int,int|null>}
     */
    private function buildLabels(int $rootId, $familyRows): array
    {
        $byParent = $familyRows->groupBy('parent_id');

        $labels = [];
        $parentIds = [$rootId => null];

        $assign = function ($parentId, string $prefix) use (&$assign, $byParent, &$labels, &$parentIds) {
            $children = $byParent->get($parentId, collect())->sortBy('ComplaintID')->values();

            foreach ($children as $i => $child) {
                $num = $i + 1;
                $label = $prefix === '' ? (string) $num : $prefix . '.' . $num;

                $labels[$child->ComplaintID] = $label;
                $parentIds[$child->ComplaintID] = $parentId;

                $assign($child->ComplaintID, $label);
            }
        };

        $assign($rootId, '');

        return [$labels, $parentIds];
    }

    /**
     * Root + every descendant at any depth.
     */
    public function query(Complaint $model): QueryBuilder
    {
        if (!$this->rootComplaint) {
            abort(500, 'Root complaint not set. Call forComplaint() first.');
        }

        $rootId = $this->rootComplaint->ComplaintID;

        $allIds = Complaint::descendantsOf($rootId)
            ->pluck('ComplaintID')
            ->push($rootId)
            ->unique();

        return $model->newQuery()
            ->whereIn('sfdcomplaints.ComplaintID', $allIds)
            ->orderByRaw('CASE WHEN sfdcomplaints.parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('sfdcomplaints.ComplaintID');
    }

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

    protected function getColumns(): array
    {
        return [
            Column::make('ComplaintID')->title('رقم الشكوي'),
            Column::computed('duplicate_badge')->title('النوع'),
            Column::computed('parent_complaint')->title('الأب'),
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

    protected function filename(): string
    {
        return 'ComplaintDuplicates_' . date('YmdHis');
    }
}