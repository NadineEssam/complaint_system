$(document).ready(function () {

    // let complaintId = $('#responsesTable').data('id');

    // // =========================
    // // DATATABLE
    // // =========================
    // let table = $('#responsesTable').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //         url: '/complaints/' + complaintId + '/responses/data',
    //         type: 'GET'
    //     },

    //     columns: [
    //         { data: 'id' }, // بدل complaint_id
    //         { data: 'ComplaintStatus' },
    //         { data: 'ComplaintText' },
    //         { data: 'ComplaintService' },
    //         { data: 'created_at' },
    //         { data: 'action', orderable: false, searchable: false }
    //     ]
    // });


    // =========================
    // VIEW MODAL
    // =========================
    $('#responsesTable').on('click', '.view-btn', function (e) {
        e.stopPropagation();

        let id = $(this).data('id');

        $.ajax({
            url: "/responses/" + id,
            type: "GET",
            success: function (data) {

                $('#modalComplaintId').text(data.complaint_id);
                $('#modalStatus').text(data.status_text);
                $('#modalText').text(data.ComplaintText);
                $('#modalService').text(data.service_name);
                $('#modalReason').text(data.reason_name);
                $('#modalClassify').text(data.classify_name);

                $('#responseModal').modal('show');
            }
        });
    });


    // =========================
    // DELETE
    // =========================
    $(document).on('click', '.delete-btn', function () {

        let id = $(this).data('id');

        if (!confirm('هل أنت متأكد من الحذف؟')) return;

        $.ajax({
            url: '/responses/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                alert(res.message);
                table.ajax.reload(); // 🔥 أفضل من reload الصفحة
            },
            error: function () {
                alert('حدث خطأ أثناء الحذف');
            }
        });
    });


    // =========================
    // STATUS TOGGLE
    // =========================
    function toggleFields() {
        let status = $('#statusSelect').val();

        if (status == 2 || status == 4) {
            $('#reasonSelect').prop('disabled', false);
            $('#classifySelect').prop('disabled', false);
        } else {
            $('#reasonSelect').prop('disabled', true);
            $('#classifySelect').prop('disabled', true);
        }
    }

    toggleFields();

    $('#statusSelect').on('change', function () {
        toggleFields();
    });

});