$(document).ready(function () {

    // =========================
    // STEP WIZARD
    // =========================
    var nav = $('.setup-panel a'),
        allSteps = $('.setup-content');

    allSteps.hide();
    $('#step-1').show();

    function setActiveStep(stepId) {
        nav.removeClass('btn-primary').addClass('btn-default');
        $('.setup-panel a[href="#' + stepId + '"]').addClass('btn-primary');

        allSteps.hide();
        $('#' + stepId).show();
    }

    // Step click
    nav.on('click', function (e) {
        e.preventDefault();

        if ($(this).attr('disabled')) return;

        setActiveStep($(this).attr('href').replace('#', ''));
    });

    // NEXT
    $('.nextBtn').on('click', function () {
        let currentStep = $(this).closest(".setup-content");
        let currentId = currentStep.attr("id");

        let currentLink = $('.setup-panel a[href="#' + currentId + '"]');
        let nextLink = currentLink.parent().next().find("a");

        if (nextLink.length) {
            setActiveStep(nextLink.attr("href").replace('#', ''));
        }
    });

    // PREV (THIS WAS MISSING)
    $('.prevBtn').on('click', function () {
        let currentStep = $(this).closest(".setup-content");
        let currentId = currentStep.attr("id");

        let currentLink = $('.setup-panel a[href="#' + currentId + '"]');
        let prevLink = currentLink.parent().prev().find("a");

        if (prevLink.length) {
            setActiveStep(prevLink.attr("href").replace('#', ''));
        }
    });


    $("form").on("submit", function () {
        $("#step-1, #step-2").find(":input").prop("disabled", false);
    });


    // =========================
    // RESULT OBJECT
    // =========================
    function createResult() {
        return {
            status: null,
            message: "",
            birthday: "",
            age: 0,
            gender: "",
            gov_info: {},
        };
    }

    // =========================
    // NATIONAL ID VALIDATION
    // =========================
    function national_no_validate(e) {

        let result = createResult();

        if (!e) {
            return { status: false, message: "لا يوجد رقم قومي" };
        }

        e = e.trim();

        if (e.length !== 14) {
            return { status: false, message: "طول الرقم القومي يجب أن يكون 14 رقم" };
        }

        let century = e.substring(0, 1);
        let year = e.substring(1, 3);
        let month = e.substring(3, 5);
        let day = e.substring(5, 7);

        if (century == "2") year = "19" + year;
        else if (century == "3") year = "20" + year;
        else {
            return { status: false, message: "القرن غير صحيح" };
        }

        let birthdate = `${year}-${month}-${day}`;
        let today = new Date().toISOString().split('T')[0];

        if (isNaN(Date.parse(birthdate))) {
            return { status: false, message: "تاريخ الميلاد غير صحيح" };
        }

        if (birthdate > today) {
            return { status: false, message: "تاريخ الميلاد أكبر من اليوم" };
        }

        result.birthday = birthdate;

        // AGE
        let birth = new Date(birthdate);
        let now = new Date();
        let age = now.getFullYear() - birth.getFullYear();
        result.age = age;

        // GOVERNORATE
        let govCode = e.substring(7, 9);

        const govMap = {
            "01": "القاهرة",
            "02": "الإسكندرية",
            "03": "بورسعيد",
            "04": "السويس",
            "11": "دمياط",
            "12": "الدقهلية",
            "13": "الشرقية",
            "14": "القليوبية",
            "15": "كفر الشيخ",
            "16": "الغربية",
            "17": "المنوفية",
            "18": "البحيرة",
            "19": "الإسماعيلية",
            "21": "الجيزة",
            "22": "بني سويف",
            "23": "الفيوم",
            "24": "المنيا",
            "25": "أسيوط",
            "26": "سوهاج",
            "27": "قنا",
            "28": "أسوان",
            "29": "الأقصر",
            "31": "البحر الأحمر",
            "32": "الوادي الجديد",
            "33": "مطروح",
            "34": "شمال سيناء",
            "35": "جنوب سيناء",
            "88": "خارج البلاد"
        };

        if (!govMap[govCode]) {
            return { status: false, message: "كود المحافظة غير صحيح" };
        }

        result.gov_info = govMap[govCode];

        // GENDER
        result.gender = (parseInt(e.substring(12, 13)) % 2 === 0) ? "أنثى" : "ذكر";

        return {
            status: true,
            message: "رقم قومي صحيح",
            birthday: result.birthday,
            age: result.age,
            gender: result.gender,
            gov: result.gov_info
        };
    }

    // =========================
    // NATIONAL ID INPUT HANDLER
    // =========================
    $(document).on("keyup", "input[name='ComplaintNationalID']", function () {

        let value = $(this).val();
        let result = national_no_validate(value);

        if (result.status) {

            $("input[name='ComplainerGender']").val(result.gender);
            $("#nidError").text("");
            $(this).removeClass("is-invalid");

        } else {

            $("input[name='ComplainerGender']").val("");
            $("#nidError").text(result.message);
            $(this).addClass("is-invalid");
        }
    });

    // =========================
    // REQUEST TYPE CONTROL
    // =========================
    $("#requesttypeid").on("change", function () {

        let value = $(this).val();

        if (value == 2) {
            $("input[name='ComplaintNationalID']").attr('required', true);
        } else {
            $("input[name='ComplaintNationalID']").removeAttr('required');
        }
    });

    // =========================
    // GOVERNORATE FILTER OFFICE
    // =========================
    $("#governorateSelect").on("change", function () {

        let selectedGov = $(this).val();

        $("#officeSelect option").each(function () {

            let optionGov = $(this).data("gov");

            if (!optionGov) return;

            if (optionGov == selectedGov) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        $("#officeSelect").val("");
    });

});