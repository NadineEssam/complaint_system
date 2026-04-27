// عند الضغط على زر عرض
// $('#responsesTable tbody').on('click', '.view-btn', function (e) {
//     e.stopPropagation();

//     let id = $(this).data('id');

//     $.ajax({
//         url: "/responses/" + id, // هنعدلها ترجع JSON
//         type: "GET",
//         success: function (data) {

//             // تعبئة البيانات
//             $('#modalComplaintId').text(data.complaint_id);
//             $('#modalStatus').text(data.status_text);
//             $('#modalText').text(data.ComplaintText);
//             $('#modalService').text(data.service_name);
//             $('#modalReason').text(data.reason_name);
//             $('#modalClassify').text(data.classify_name);

//             // فتح المودال
//             $('#responseModal').modal('show');
//         }
//     });
// });
$(document).ready(function () {

    let complaintId = $('#responsesTable').data('id');

    $('#responsesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/complaints/' + complaintId + '/responses/data',

        columns: [
            { data: 'complaint_id' },
            { data: 'ComplaintStatus' },
            { data: 'ComplaintText' },
            { data: 'ComplaintService' },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

});
$(document).ready(function () {

    function toggleFields() {
        let status = $('#statusSelect').val();

        // 👇 غيري الأرقام حسب الداتا عندك
        if (status == 2 || status == 4) { // تم الحل أو تم الحفظ
            $('#reasonSelect').prop('disabled', false);
            $('#classifySelect').prop('disabled', false);
        } else {
            $('#reasonSelect').prop('disabled', true);
            $('#classifySelect').prop('disabled', true);
        }
    }

    // عند تحميل الصفحة
    toggleFields();

    // عند تغيير الحالة
    $('#statusSelect').on('change', function () {
        toggleFields();
    });

});

$(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');

    if (!confirm('هل أنت متأكد من الحذف؟')) return;

    $.ajax({
        url: '/responses/' + id,
        type: 'POST', 
        data: {
            _method: 'DELETE', 
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert(res.message);
            location.reload();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('حدث خطأ أثناء الحذف');
        }
    });
});
