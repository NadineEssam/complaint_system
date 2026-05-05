$(document).ready(function () {

    // =========================
    // DATA TABLE (SAFE INIT)
    // =========================
    const table = $('#complaintsTable').length
        ? $('#complaintsTable').DataTable()
        : null;

    // =========================
    // STEP WIZARD SETUP
    // =========================
    var nav = $('.setup-panel a'),
        allSteps = $('.setup-content'),
        nextBtn = $('.nextBtn');

    allSteps.hide();
    $('#step-1').show();

    // CLICK ON STEP HEADER
    nav.on('click', function (e) {
        e.preventDefault();

        if ($(this).attr('disabled')) return;

        var target = $($(this).attr('href'));

        nav.removeClass('btn-primary').addClass('btn-default');
        $(this).addClass('btn-primary');

        allSteps.hide();
        target.show();
    });

    // NEXT BUTTON
    nextBtn.on('click', function () {

        let currentStep = $(this).closest(".setup-content");
        let currentId = currentStep.attr("id");

        let currentLink = $('.setup-panel a[href="#' + currentId + '"]');
        let nextLink = currentLink.parent().next().find("a");

        if (nextLink.length) {

            nextLink.removeAttr('disabled');

            nav.removeClass('btn-primary').addClass('btn-default');
            nextLink.addClass('btn-primary');

            allSteps.hide();
            $(nextLink.attr("href")).show();
        }
    });

    // =========================
    // REQUEST TYPE → NATIONAL ID
    // =========================
    $("#requesttypeid").on("change", function () {

    let value = $(this).val();

    toggleNationalIdRequired(value);
});

function toggleNationalIdRequired() {

    let value = $("#requesttypeid").val();

    // always show section
    $("#nationalIdSection").show();

    if (value == 2) {

        $("input[name='ComplaintNationalID']").attr('required', true);
        $("input[name='ComplainerGender']").attr('required', true);

    } else {

        $("input[name='ComplaintNationalID']").removeAttr('required');
        $("input[name='ComplainerGender']").removeAttr('required');
    }
}

// run on change
$("#requesttypeid").on("change", toggleNationalIdRequired);

// run on load (VERY IMPORTANT for edit page)
toggleNationalIdRequired();

    // =========================
    // GENDER DETECTION FROM NATIONAL ID
    // =========================
    $(document).on("keyup", "input[name='ComplaintNationalID']", function () {

        let val = $(this).val();

        if (val.length > 0) {

            let lastDigit = val.slice(-1);

            if (parseInt(lastDigit) % 2 === 0) {
                $("input[name='ComplainerGender']").val("أنثى");
            } else {
                $("input[name='ComplainerGender']").val("ذكر");
            }
        }
    });

    // =========================
    // GOVERNORATE → OFFICE FILTER
    // =========================
    $("#governorateSelect").on("change", function () {
        let selectedGov = $(this).val();

        $("#officeSelect option").each(function () {
            let optionGov = $(this).data("gov");
            if (!optionGov) return; // placeholder

            if (optionGov == selectedGov) $(this).show();
            else $(this).hide();
        });

        // Reset office
        $("#officeSelect").val("");
    });

    // =========================
    // SELECT2 (COM SOURCES)
    // =========================
    // if ($.fn.select2) {
    //     $("select[name='comsources[]']").select2({
    //         placeholder: "اختر مصدر الشكوى",
    //         allowClear: true,
    //         width: "100%"
    //     });
    // }

    // =========================
    // STATUS CONTROL (SAFE IF EXISTS)
    // =========================
    $("#ComplaintStatus").on("change", function () {

        let status = $(this).val();

        if (status == 1 || status == 3) {
            $("#closeReason").prop("disabled", true);
            $("#closeReasonClassify").prop("disabled", true);
        } else {
            $("#closeReason").prop("disabled", false);
            $("#closeReasonClassify").prop("disabled", false);
        }
    });

    // =========================
    // CLOSE REASON FILTER
    // =========================
    $("#closeReason").on("change", function () {

        let selected = $(this).val();

        $("#closeReasonClassify option").each(function () {

            let parent = $(this).data("parent");

            if (!parent) return;

            if (parent == selected) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        $("#closeReasonClassify").val("");
    });

    // =========================
    // DELETE COMPLAINT (AJAX SAFE)
    // =========================
    $(document).on('click', '.delete-btn', function () {

        let id = $(this).data('id');

        if (!confirm('هل أنت متأكد من الحذف؟')) return;

        $.ajax({
            url: '/complaints/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                alert(response.message || 'Deleted successfully');

                if (table) {
                    table.ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                alert('حدث خطأ أثناء الحذف');
                console.log(xhr.responseText);
            }
        });
    });
$("form").on("submit", function () {
    $("#step-1, #step-2").find(":input").prop("disabled", false);
});
});