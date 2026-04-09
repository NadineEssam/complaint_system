$(document).ready(function () {
    // show/hide national id
    $("#requesttypeid").on("change", function () {
        let value = $(this).val();

        if (value == 2) {
            $("#nationalIdSection").show();
        } else {
            $("#nationalIdSection").hide();
            $("#national_id").val("");
            $("#gender").val("");
        }
    });

    // detect gender
    $("#national_id").on("keyup", function () {
        let val = $(this).val();

        if (val.length > 0) {
            let lastDigit = val.slice(-1);

            if (lastDigit % 2 == 0) {
                $("#gender").val("أنثى");
            } else {
                $("#gender").val("ذكر");
            }
        }
    });

    // Filter offices by governorate
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

    $("select[name='comsources[]']").select2({
        placeholder: "اختر مصدر الشكوى",
        allowClear: true,
        width: "100%",
    });

    // Enable/Disable based on status
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

// Filter classification based on reason
$("#closeReason").on("change", function () {
    let selected = $(this).val();

    $("#closeReasonClassify option").each(function () {
        let parent = $(this).data("parent");

        if (!parent) return;

        if (parent == selected) $(this).show();
        else $(this).hide();
    });

    $("#closeReasonClassify").val("");
});

 
});
