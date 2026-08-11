<style>
    #complaint-duplicates-table {
        direction: rtl;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .dt-top-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .dt-length-control {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: #6c757d;
    }
    .dt-length-control select {
        border: 1px solid #e3e6ea;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13.5px;
        color: #344054;
        background: #fff;
        cursor: pointer;
    }
    .dt-search-control {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #e3e6ea;
        border-radius: 10px;
        padding: 8px 16px;
    }
    .dt-search-control input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13.5px;
        width: 180px;
        color: #344054;
    }
    .dt-search-control i {
        color: #98a2b3;
        font-size: 16px;
    }

    .legend-row {
        display: flex;
        align-items: center;
        gap: 22px;
        margin-bottom: 16px;
        padding: 12px 18px;
        background: #f9fafb;
        border-radius: 12px;
        font-size: 12.5px;
        color: #475467;
        flex-wrap: wrap;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 7px;
        font-weight: 500;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .legend-dot.original { background: #2970ff; }
    .legend-dot.duplicate { background: #98a2b3; }
    .legend-dot.current { background: #2970ff; }

    #complaint-duplicates-table {
        width: 100% !important;
        background: transparent;
    }
    #complaint-duplicates-table thead th {
        background: #f9fafb;
        color: #475467;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: .2px;
        padding: 12px 18px;
        border: none !important;
        text-align: right;
        white-space: nowrap;
    }
    #complaint-duplicates-table thead th:first-child { border-radius: 10px 0 0 10px; }
    #complaint-duplicates-table thead th:last-child { border-radius: 0 10px 10px 0; }

    #complaint-duplicates-table tbody tr {
        background: #fff;
        box-shadow: 0 1px 2px rgba(16,24,40,0.04), 0 0 0 1px #eaecf0;
        transition: box-shadow .15s, transform .1s;
    }
    #complaint-duplicates-table tbody tr:hover {
        box-shadow: 0 4px 10px rgba(16,24,40,0.08), 0 0 0 1px #d0d5dd;
    }
    #complaint-duplicates-table tbody td {
        padding: 16px 18px;
        font-size: 13.5px;
        color: #344054;
        vertical-align: middle;
        text-align: right;
        border: none !important;
    }
    #complaint-duplicates-table tbody td:first-child { border-radius: 12px 0 0 12px; }
    #complaint-duplicates-table tbody td:last-child { border-radius: 0 12px 12px 0; }

    .complaint-id {
        font-weight: 700;
        color: #1d2939;
        font-size: 14px;
    }

    #complaint-duplicates-table tbody tr.current-complaint-row {
        background: #f5f9ff !important;
        box-shadow: inset 3px 0 0 0 #2970ff, 0 1px 2px rgba(16,24,40,0.04), 0 0 0 1px #d1e0ff !important;
    }

    .you-are-here {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #eaf2ff;
        color: #2970ff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        margin-right: 10px;
        white-space: nowrap;
        border: none;
    }
    .you-are-here i { font-size: 13px; }

    .badge-level {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
    }
    .badge-level.original {
        background: #eff8ff;
        color: #175cd3;
    }
    .badge-level.duplicate {
        background: #f2f4f7;
        color: #475467;
    }

    .parent-link-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 8px;
        background: #f9fafb;
        color: #475467;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #eaecf0;
    }
    .parent-link-none {
        color: #d0d5dd;
        font-size: 13px;
    }

    .status-pill {
        display: inline-block;
        padding: 5px 13px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pill.s-new      { background: #eff8ff; color: #175cd3; }
    .status-pill.s-progress { background: #fff4e5; color: #b54708; }
    .status-pill.s-done     { background: #ecfdf3; color: #027a48; }
    .status-pill.s-neutral  { background: #f2f4f7; color: #475467; }

    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .dt-bottom-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 18px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .dt-info {
        font-size: 12.5px;
        color: #98a2b3;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        display: none !important;
    }
    .custom-dt-pagination {
        display: flex;
        gap: 4px;
    }
    .custom-dt-pagination button {
        border: 1px solid #e3e6ea;
        background: #fff;
        color: #344054;
        padding: 6px 13px;
        border-radius: 8px;
        font-size: 12.5px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .custom-dt-pagination button:hover,
    .custom-dt-pagination button.active {
        background: #2970ff;
        color: #fff;
        border-color: #2970ff;
    }
    .custom-dt-pagination button:disabled {
        opacity: 0.4;
        cursor: default;
    }
</style>

<div dir="rtl">

    <div class="legend-row">
        <div class="legend-item">
            <span class="legend-dot original"></span>
            الأصل
        </div>
        <div class="legend-item">
            <span class="legend-dot duplicate"></span>
            تكرار
        </div>
        <div class="legend-item">
            <span class="legend-dot current"></span>
            البيان الذي تعرضه الآن
        </div>
    </div>

    <div class="dt-top-controls">
        <div class="dt-length-control">
            <span>عرض</span>
            <select id="dt-page-length">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span>سجلات</span>
        </div>
        <div class="dt-search-control">
            <i class="bx bx-search"></i>
            <input type="text" id="dt-search-input" placeholder="بحث...">
        </div>
    </div>

    <div class="table-responsive">
        <table id="complaint-duplicates-table" class="table">
            <thead>
                <tr>
                    <th>رقم البيان</th>
                    <th>النوع</th>
                    <th>الأب</th>
                    <th>نوع الطلب</th>
                    <th>الحالة</th>
                    <th>تاريخ البيان</th>
                    <th>الاجراءات</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="dt-bottom-controls">
        <div class="dt-info" id="dt-custom-info"></div>
        <div class="custom-dt-pagination" id="dt-custom-pagination"></div>
    </div>
</div>

<script>
    (function () {
        if ($.fn.DataTable && $.fn.dataTable.isDataTable('#complaint-duplicates-table')) {
            $('#complaint-duplicates-table').DataTable().destroy();
        }

        var currentComplaintId = {{ $currentId ?? 'null' }};

        function statusPillClass(statusText) {
            if (!statusText) return 's-neutral';
            var t = statusText.trim();
            if (t.indexOf('جديد') !== -1) return 's-new';
            if (t.indexOf('حل') !== -1 || t.indexOf('غلق') !== -1 || t.indexOf('انتهت') !== -1) return 's-done';
            if (t.indexOf('قيد') !== -1 || t.indexOf('جاري') !== -1) return 's-progress';
            return 's-neutral';
        }

        var table = $('#complaint-duplicates-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('complaints.duplicates.index', $complaint) }}",
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            columns: [
                {
                    data: 'ComplaintID',
                    name: 'ComplaintID',
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        var isCurrent = currentComplaintId && parseInt(data) === parseInt(currentComplaintId);
                        var youAreHere = isCurrent ? '<span class="you-are-here"><i class="bx bx-map-pin"></i> أنت هنا</span>' : '';
                        return '<span class="complaint-id">#' + data + '</span>' + youAreHere;
                    }
                },
                { data: 'duplicate_badge', name: 'duplicate_badge', orderable: false, searchable: false },
                { data: 'parent_complaint', name: 'parent_complaint', orderable: false, searchable: false },
                { data: 'requesttypename', name: 'requesttype.requesttypename' },
                {
                    data: 'status_name',
                    name: 'compstatus.statusText',
                    render: function (data, type) {
                        if (type !== 'display') return data;
                        var cls = statusPillClass(data);
                        return '<span class="status-pill ' + cls + '">' + (data ?? '-') + '</span>';
                    }
                },
                { data: 'ComplaintDate', name: 'ComplaintDate' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            createdRow: function (row, data) {
                if (currentComplaintId && parseInt(data.ComplaintID) === parseInt(currentComplaintId)) {
                    $(row).addClass('current-complaint-row');
                }
            },
            drawCallback: function (settings) {
                var api = this.api();
                var info = api.page.info();

                $('#dt-custom-info').text(
                    'عرض ' + (info.start + 1) + ' إلى ' + info.end + ' من أصل ' + info.recordsTotal + ' سجل'
                );

                var pagination = $('#dt-custom-pagination').empty();
                var btnPrev = $('<button>السابق</button>').prop('disabled', info.page === 0);
                btnPrev.on('click', function () { api.page('previous').draw('page'); });
                pagination.append(btnPrev);

                for (var i = 0; i < info.pages; i++) {
                    (function(page) {
                        var btn = $('<button>' + (page + 1) + '</button>');
                        if (page === info.page) btn.addClass('active');
                        btn.on('click', function () { api.page(page).draw('page'); });
                        pagination.append(btn);
                    })(i);
                }

                var btnNext = $('<button>التالي</button>').prop('disabled', info.page === info.pages - 1);
                btnNext.on('click', function () { api.page('next').draw('page'); });
                pagination.append(btnNext);
            }
        });

        $('#dt-search-input').on('keyup', function () {
            table.search(this.value).draw();
        });

        $('#dt-page-length').on('change', function () {
            table.page.len(parseInt(this.value)).draw();
        });
    })();
</script>