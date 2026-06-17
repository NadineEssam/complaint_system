<div dir="rtl">
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

<script>
    $(document).ready(function () {

    if ($.fn.DataTable && $.fn.dataTable.isDataTable('#complaint-duplicates-table'))  {
        $('#complaint-duplicates-table').DataTable().destroy();
    }

    $('#complaint-duplicates-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('complaints.duplicates.index', $root) }}",
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
        ]
    });

});
</script>