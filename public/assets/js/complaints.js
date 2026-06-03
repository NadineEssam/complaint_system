$(document).ready(function () {

  // =========================
// STEP WIZARD
// =========================
var nav = $('.setup-panel a'),
    allSteps = $('.setup-content');

allSteps.hide();
$('#step-1').show();

function setActiveStep(stepId) {

    allSteps.removeClass('active').hide();

    $('#' + stepId)
        .addClass('active')
        .show();

    nav.removeClass('active btn-primary')
        .addClass('btn-default');

    $('.setup-panel a[href="#' + stepId + '"]')
        .addClass('active btn-primary');
}


// =========================
// INITIAL STEP
// =========================

// لو فيه أخطاء في step 2
let hasStep2Errors =
    $('[name="ComplaintDate"]').hasClass('is-invalid') ||
    $('[name="sector_id"]').hasClass('is-invalid') ||
    $('[name="ComplaintGovernorate"]').hasClass('is-invalid') ||
    $('[name="office"]').hasClass('is-invalid') ||
    $('[name="comsource_id"]').hasClass('is-invalid');

// لو فيه أخطاء في step 1
let hasStep1Errors =
    $('[name="requesttypeid"]').hasClass('is-invalid') ||
    $('[name="ComplainerName"]').hasClass('is-invalid') ||
    $('[name="ComplainerEmail"]').hasClass('is-invalid') ||
    $('[name="ComplainerPhone"]').hasClass('is-invalid') ||
    $('[name="ComplaintNationalID"]').hasClass('is-invalid');

if (hasStep2Errors) {

    setActiveStep('step-2');

} else {

   setActiveStep(window.currentWizardStep || 'step-1');
}


// =========================
// VALIDATION FUNCTION
// =========================
function validateStep1() {

    let isValid = true;

    $('#step-1 .form-control, #step-1 .form-select')
        .removeClass('is-invalid');

    let requestType = $('#requesttypeid').val();
    let nationalId = $("input[name='ComplaintNationalID']").val().trim();

    const requiredFields = [
        'requesttypeid',
        'ComplainerName',
        'ComplainerPhone',
        'ComplainerEmail'
    ];

    requiredFields.forEach(function (fieldName) {

        let field = $('[name="' + fieldName + '"]');

        if ($.trim(field.val()) === '') {
            field.addClass('is-invalid');
            isValid = false;
        }
    });

    // الرقم القومي مطلوب
    if (requestType == 2 && nationalId === '') {

        $("input[name='ComplaintNationalID']")
            .addClass('is-invalid');

        $("#nidError").text("الرقم القومي مطلوب");

        isValid = false;
    }

    return isValid;
}


// =========================
// STEP CLICK
// =========================
nav.on('click', function (e) {

    e.preventDefault();

    let target = $(this).attr('href').replace('#', '');

    // لو رايح step 2 لازم validation
    if (target === 'step-2') {

        if (!validateStep1()) {

            alert("من فضلك قم بإكمال جميع البيانات المطلوبة");

            return false;
        }
    }

    setActiveStep(target);
});


// =========================
// NEXT BUTTON
// =========================
$('.nextBtn').on('click', function () {

    if (!validateStep1()) {

        alert("من فضلك قم بإكمال جميع البيانات المطلوبة");

        return false;
    }

    setActiveStep('step-2');
});


// =========================
// PREV BUTTON
// =========================
$('.prevBtn').on('click', function () {

    setActiveStep('step-1');
});


 $("form").on("submit", function (e) {

    let step1Valid = validateStep1();
    let step2Valid = validateStep2();

    if (!step1Valid) {

        e.preventDefault();

        setActiveStep('step-1');

        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'من فضلك استكمل البيانات المطلوبة في البيانات الشخصية'
        });

        return false;
    }

    if (!step2Valid) {

        e.preventDefault();

        setActiveStep('step-2');

        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'من فضلك استكمل البيانات المطلوبة في تفاصيل الشكوى'
        });

        return false;
    }

    return true;
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




function validateStep1() {

    let isValid = true;

    $('#step-1 .form-control, #step-1 .form-select')
        .removeClass('is-invalid');

    let requestType = $('#requesttypeid').val();

    let nationalId = $("input[name='ComplaintNationalID']").val().trim();

    let phone = $("input[name='ComplainerPhone']").val().trim();

    let email = $("input[name='ComplainerEmail']").val().trim();

    const requiredFields = [
        'requesttypeid',
        'ComplainerName',
        'ComplainerPhone',
        'ComplainerEmail'
    ];

    requiredFields.forEach(function (fieldName) {

        let field = $('[name="' + fieldName + '"]');

        if ($.trim(field.val()) === '') {

            field.addClass('is-invalid');

            isValid = false;
        }
    });

    // VALID PHONE
    let phoneRegex = /^01[0-2,5]{1}[0-9]{8}$/;

    if (phone !== '' && !phoneRegex.test(phone)) {

        $("input[name='ComplainerPhone']").addClass('is-invalid');

        isValid = false;
    }

    // VALID EMAIL
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email !== '' && !emailRegex.test(email)) {

        $("input[name='ComplainerEmail']").addClass('is-invalid');

        isValid = false;
    }

    // NATIONAL ID
    if (requestType == 2 && nationalId === '') {

        $("input[name='ComplaintNationalID']")
            .addClass('is-invalid');

        $("#nidError").text("الرقم القومي مطلوب");

        isValid = false;
    }

    return isValid;
}


$(document).on("keyup", "input[name='ComplainerPhone']", function () {

    let phone = $(this).val().trim();

    let regex = /^01[0-2,5]{1}[0-9]{8}$/;

    if (phone === '') {

        $(this).removeClass("is-invalid");

        return;
    }

    if (!regex.test(phone)) {

       
$(this).addClass("is-invalid");
if ($(this).next('.error-text').length === 0) {
    $(this).after('<div class="error-text">رقم الهاتف غير صحيح</div>');
}

    } else {

        $(this).removeClass("is-invalid");
$(this).next('.error-text').remove();
    }
});


$(document).on("keyup", "input[name='ComplainerEmail']", function () {
    let email = $(this).val().trim();
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let errorSpan = $(this).siblings('.error-text');

    if (email === '') {
        $(this).removeClass("is-invalid");
        errorSpan.text('');
        return;
    }

    if (!regex.test(email)) {
        $(this).addClass("is-invalid");
        errorSpan.text('يرجى إدخال البريد الإلكتروني ');
    } else {
        $(this).removeClass("is-invalid");
        errorSpan.text('');
    }
});

$(document).on("keyup", "input[name='ComplainerEmail']", function () {

    let email = $(this).val().trim();

    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === '') {

        $(this).removeClass("is-invalid");

        return;
    }

    if (!regex.test(email)) {

        $(this).addClass("is-invalid");

if ($(this).next('.error-text').length === 0) {
    $(this).after('<div class="error-text">البريد الإلكتروني غير صحيح</div>');
}

    } else {

        $(this).removeClass("is-invalid");
$(this).next('.error-text').remove();
    }
});
});