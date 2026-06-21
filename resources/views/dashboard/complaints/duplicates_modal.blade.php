<style>
    #complaint-duplicates-table {
        direction: rtl;
    }
    .dt-top-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .dt-length-control {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #495057;
    }
    .dt-length-control select {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 14px;
        color: #212529;
        background: #f8f9fa;
        cursor: pointer;
    }
    .dt-search-control {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 6px 14px;
    }
    .dt-search-control input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 14px;
        width: 180px;
        color: #212529;
    }
    .dt-search-control i {
        color: #adb5bd;
        font-size: 16px;
    }
    #complaint-duplicates-table thead th {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        padding: 12px 16px;
        border: none;
        text-align: right;
        white-space: nowrap;
    }
    #complaint-duplicates-table tbody tr {
        transition: background 0.15s;
    }
    #complaint-duplicates-table tbody tr:hover {
        background: #f0f5ff;
    }
    #complaint-duplicates-table tbody td {
        padding: 12px 16px;
        font-size: 14px;
        color: #212529;
        vertical-align: middle;
        text-align: right;
        border-color: #f0f0f0;
    }
    .dt-bottom-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .dt-info {
        font-size: 13px;
        color: #6c757d;
    }
    /* Override default DataTables controls to hide them */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        display: none !important;
    }
    /* Custom pagination */
    .custom-dt-pagination {
        display: flex;
        gap: 4px;
    }
    .custom-dt-pagination button {
        border: 1px solid #dee2e6;
        background: #fff;
        color: #0d6efd;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .custom-dt-pagination button:hover,
    .custom-dt-pagination button.active {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .custom-dt-pagination button:disabled {
        opacity: 0.4;
        cursor: default;
    }
</style>

<div dir="rtl">
    {{-- Top controls --}}
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

    {{-- Table --}}
    <div class="table-responsive">
        <table id="complaint-duplicates-table" class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>رقم الشكوي</th>
                    <th>النوع</th>
                    <th>نوع الطلب</th>
                    <th>الحالة</th>
                    <th>تاريخ الشكوي</th>
                    <th>الاجراءات</th>
                </tr>
            </thead>
        </table>
    </div>

    {{-- Bottom controls --}}
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

        var table = $('#complaint-duplicates-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('complaints.duplicates.index', $complaint) }}",
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            columns: [
                { data: 'ComplaintID', name: 'ComplaintID' },
                { data: 'duplicate_badge', name: 'duplicate_badge', orderable: false, searchable: false },
                { data: 'requesttypename', name: 'requesttype.requesttypename' },
                { data: 'status_name', name: 'compstatus.statusText' },
                { data: 'ComplaintDate', name: 'ComplaintDate' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            drawCallback: function (settings) {
                var api = this.api();
                var info = api.page.info();

                // Update info text
                $('#dt-custom-info').text(
                    'عرض ' + (info.start + 1) + ' إلى ' + info.end + ' من أصل ' + info.recordsTotal + ' سجل'
                );

                // Build pagination
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

        // Custom search
        $('#dt-search-input').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Custom page length
        $('#dt-page-length').on('change', function () {
            table.page.len(parseInt(this.value)).draw();
        });
    })();
</script>