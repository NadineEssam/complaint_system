// عند الضغط على زر عرض
$('#responsesTable tbody').on('click', '.view-btn', function (e) {
    e.stopPropagation();

    let id = $(this).data('id');

    $.ajax({
        url: "/responses/" + id, // هنعدلها ترجع JSON
        type: "GET",
        success: function (data) {

            // تعبئة البيانات
            $('#modalComplaintId').text(data.complaint_id);
            $('#modalStatus').text(data.status_text);
            $('#modalText').text(data.ComplaintText);
            $('#modalService').text(data.service_name);
            $('#modalReason').text(data.reason_name);
            $('#modalClassify').text(data.classify_name);

            // فتح المودال
            $('#responseModal').modal('show');
        }
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

