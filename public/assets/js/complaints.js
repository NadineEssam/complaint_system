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
        $('[name="sec_id"]').hasClass('is-invalid') ||
        $('[name="ComplaintGovernorate"]').hasClass('is-invalid') ||
        $('[name="office"]').hasClass('is-invalid') ||
        $('[name="comsource_id"]').hasClass('is-invalid') ||
        $('[name="ComplaintText"]').hasClass('is-invalid');

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
            'ComplainerGovernorate'
        ];

        if (requestType == 5) {
            requiredFields.push('ComplainerEmail');
        }

        requiredFields.forEach(function (fieldName) {

            let field = $('[name="' + fieldName + '"]');

            if ($.trim(field.val()) === '') {
                field.addClass('is-invalid');
                isValid = false;
            }
        });

        // email validation
        if (requestType == 5) {

            let emailField = $('[name="ComplainerEmail"]');
            let email = emailField.val().trim();
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                emailField.addClass('is-invalid');
                isValid = false;
            }
        }

        // national ID
        if ((requestType == 2 || requestType == 3) && nationalId === '') {

            $("input[name='ComplaintNationalID']").addClass('is-invalid');
            $("#nidError").text("الرقم القومي مطلوب");
            isValid = false;
        }

        // gender
        if (requestType == 1 || requestType == 4 || requestType == 5) {

            let genderVal = $("#ComplainerGenderSelect").val();

            if (!genderVal || genderVal === '') {
                $("#ComplainerGenderSelect").addClass('is-invalid');
                $("#genderError").text("يرجى اختيار النوع.");
                isValid = false;
            }
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

                Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'من فضلك قم بإكمال جميع البيانات المطلوبة' });

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

            Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'من فضلك قم بإكمال جميع البيانات المطلوبة' });

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
                text: 'من فضلك استكمل البيانات المطلوبة في تفاصيل البيان'
            });

            return false;
        }


        if ($("#requesttypeid").val() == 1 || $("#requesttypeid").val() == 4 || $("#requesttypeid").val() == 5) {
            let selectedGender = $("#ComplainerGenderSelect").val();
            $("#ComplainerGenderReadonly").val(selectedGender).removeAttr('readonly');
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

        if ($("#requesttypeid").val() != 2 && $("#requesttypeid").val() != 3) return;

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

    
   
    // Run on page load to set correct state
    $("#requesttypeid").trigger("change");

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
    $(document).on("change", "[name='ComplainerGovernorate']", function () {
        if ($(this).val() !== '') {
            $(this).removeClass('is-invalid');
            $("#complainerGovError").text('');
        }
    });



    // =========================
    // VALIDATE STEP 2
    // =========================
    //     function validateStep2() {
    //         let isValid = true;
    //         $('#step-2 .form-control, #step-2 .form-select').removeClass('is-invalid');
    //         $("#complaintTextError").text('');

    //         // المحافظة required
    //         if (!$('[name="ComplaintGovernorate"]').val()) {
    //             $('[name="ComplaintGovernorate"]').addClass('is-invalid');
    //             $("#governorateError").text("يرجى اختيار المحافظة.");
    //             isValid = false;
    //         } else {
    //             $("#governorateError").text('');
    //         }

    //         // الفرع required
    //         if (!$('[name="office"]').val()) {
    //             $('[name="office"]').addClass('is-invalid');
    //             $("#officeError").text("يرجى اختيار الفرع.");
    //             isValid = false;
    //         } else {
    //             $("#officeError").text('');
    //         }

    //         // مصدر البيان required
    //         // if (!$('[name="comsource_id"]').val()) {
    //         //     $('[name="comsource_id"]').addClass('is-invalid');
    //         //     $("#comsourceError").text("يرجى اختيار مصدر البيان.");
    //         //     isValid = false;
    //         // } else {
    //         //     $("#comsourceError").text('');
    //         // }

    //         // مصدر البيان required (MULTI-SELECT)
    //        if (!$('#comsourceSelect').val() || $('#comsourceSelect').val().length === 0) {

    //         $('#comsourceSelect')
    //             .next('.select2-container')
    //             .find('.select2-selection')
    //             .addClass('is-invalid');

    //         $("#comsourceError").text("يرجى اختيار مصدر البيان.");
    //         isValid = false;

    //     } else {

    //         $('#comsourceSelect')
    //             .next('.select2-container')
    //             .find('.select2-selection')
    //             .removeClass('is-invalid');

    //         $("#comsourceError").text('');
    //     }

    //         // نص البيان required
    //         if ($("textarea[name='ComplaintText']").val().trim() === '') {
    //             $("textarea[name='ComplaintText']").addClass('is-invalid');
    //             $("#complaintTextError").text("يرجى إدخال نص البيان.");
    //             isValid = false;
    //         }



    //         // Replace the governorate and office validation inside validateStep2():

    //  $("#complaintTextError, #governorateError, #officeError").text('');

    //     let cType = $('#complaint_type').val();

    //     // complaint_type itself required
    //     if (!cType) {
    //         $('#complaint_type').addClass('is-invalid');
    //         isValid = false;
    //     }

    //     // المحافظة — only for internal
    //     if (cType === 'internal') {
    //         if (!$('[name="ComplaintGovernorate"]').val()) {
    //             $('[name="ComplaintGovernorate"]').addClass('is-invalid');
    //             $("#governorateError").text("يرجى اختيار المحافظة.");
    //             isValid = false;
    //         }
    //         if (!$('[name="office"]').val()) {
    //             $('[name="office"]').addClass('is-invalid');
    //             $("#officeError").text("يرجى اختيار الفرع.");
    //             isValid = false;
    //         }
    //     }

    //     // الإدارة — only required for external AND sector has departments
    //     if (cType === 'external') {
    //         if (!$('[name="sec_id"]').val()) {
    //             $('[name="sec_id"]').addClass('is-invalid');
    //             isValid = false;
    //         }

    //         // department required only if sector has departments
    //         let hasDeptsVisible = $('#department option[data-sector]:visible').length > 0;
    //         if (hasDeptsVisible && !$('[name="department"]').val()) {
    //             $('[name="department"]').addClass('is-invalid');
    //             isValid = false;
    //         }
    //     }

    //         return isValid;
    //     }

    function validateStep2() {
        let isValid = true;
        $('#step-2 .form-control, #step-2 .form-select').removeClass('is-invalid');
        $("#complaintTextError, #governorateError, #officeError").text('');

        let cType = $('#complaint_type').val();

        // نوعية وتوجيه البيان required
        if (!cType) {
            $('#complaint_type').addClass('is-invalid');
            isValid = false;
        }

        if (cType === 'external') {          // ← was 'internal'
            if (!$('[name="ComplaintGovernorate"]').val()) {
                $('[name="ComplaintGovernorate"]').addClass('is-invalid');
                $("#governorateError").text("يرجى اختيار المحافظة.");
                isValid = false;
            }
        }

        if (cType === 'internal') {          // ← was 'external'
            if (!$('[name="sec_id"]').val()) {
                $('[name="sec_id"]').addClass('is-invalid');
                isValid = false;
            }
            let hasDeptsVisible = $('#department option[data-sector]:visible').length > 0;
            if (hasDeptsVisible && !$('[name="department"]').val()) {
                $('[name="department"]').addClass('is-invalid');
                isValid = false;
            }
        }

        // نوع النشاط required
        // let projectTypeVal = $('#ComplaintProjectType').val();
        // if (!projectTypeVal || projectTypeVal === '') {
        //     $('#ComplaintProjectType').addClass('is-invalid');
        //     isValid = false;
        // } else {
        //     $('#ComplaintProjectType').removeClass('is-invalid');
        // }
        // مصدر البيان required always
        if (!$('#comsourceSelect').val() || $('#comsourceSelect').val().length === 0) {
            $('#comsourceSelect')
                .next('.select2-container')
                .find('.select2-selection')
                .addClass('is-invalid');
            $("#comsourceError").text("يرجى اختيار مصدر البيان.");
            isValid = false;
        } else {
            $('#comsourceSelect')
                .next('.select2-container')
                .find('.select2-selection')
                .removeClass('is-invalid');
            $("#comsourceError").text('');
        }

        // نص البيان required always
        if ($("textarea[name='ComplaintText']").val().trim() === '') {
            $("textarea[name='ComplaintText']").addClass('is-invalid');
            $("#complaintTextError").text("يرجى إدخال نص البيان.");
            isValid = false;
        }

        return isValid;
    }

    // =========================
    // GENDER DROPDOWN CLEAR ERROR
    // =========================
    $(document).on("change", "#ComplainerGenderSelect", function () {
        if ($(this).val() !== '') {
            $(this).removeClass('is-invalid');
            $("#genderError").text('');
        }
    });

    // =========================
    // COMPLAINT TEXT CLEAR ERROR
    // =========================
    $(document).on("input", "textarea[name='ComplaintText']", function () {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
            $("#complaintTextError").text('');
        }
    });

    // =========================
    // PHONE LIVE VALIDATION
    // =========================
    $(document).on("keyup", "input[name='ComplainerPhone']", function () {
        let phone = $(this).val().trim();
        let regex = /^01[0-2,5]{1}[0-9]{8}$/;
        if (phone === '') {
            $(this).removeClass("is-invalid");
            $(this).next('.error-text').remove();
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

    // =========================
    // EMAIL LIVE VALIDATION
    // =========================
    $(document).on("keyup", "input[name='ComplainerEmail']", function () {

        let email = $(this).val().trim();
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === '') {

            $(this).removeClass("is-invalid");
            $(this).next('.error-text').remove();
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



    // =========================
    // SECTOR → FILTER DEPARTMENTS
    // =========================
    $('[name="sec_id"]').on('change', function () {
        let selectedSector = $(this).val();
        let $dept = $('#department');
        let currentVal = $dept.val();

        // Remove any "no departments" option
        $dept.find('option.no-dept').remove();

        let visibleCount = 0;

        $dept.find('option').each(function () {
            let optSector = $(this).data('sector');
            if (!optSector) return; // skip placeholder
            if (optSector == selectedSector) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        // If no departments match, add a disabled placeholder
        if (selectedSector && visibleCount === 0) {
            $dept.append('<option class="no-dept" value="" disabled selected>لا توجد إدارة</option>');
            $dept.val('');
        } else {
            // Reset if current selection no longer visible
            let stillVisible = $dept.find('option[value="' + currentVal + '"]:visible').length > 0;
            if (!stillVisible) $dept.val('');
        }
    });

    // Run on load for edit mode
    $('[name="sec_id"]').trigger('change');

    // =========================
    // PROJECT TYPE CLEAR ERROR
    // =========================
    $(document).on('change', '#ComplaintProjectType', function () {
        if ($(this).val() && $(this).val() !== '') {
            $(this).removeClass('is-invalid');
        }
    });
    // =========================
    // COMPLAINT TYPE → TOGGLE FIELDS
    // =========================
    function applyComplaintTypeToggle(val) {
        if (val === 'external') {                          // ← was 'internal'
            $('#govOfficeGroup, #officeGroup').show();
            $('#sectorGroup, #departmentGroup').hide();
            $('[name="sec_id"], [name="department"]').val('').removeClass('is-invalid');
        } else if (val === 'internal') {                   // ← was 'external'
            $('#sectorGroup, #departmentGroup').show();
            $('#govOfficeGroup, #officeGroup').hide();
            $('[name="ComplaintGovernorate"]', '#step-2').val('').removeClass('is-invalid');
            $('[name="office"]').val('').removeClass('is-invalid');
            $("#governorateError, #officeError").text('');
            $('[name="sec_id"]').trigger('change');
        } else {
            $('#govOfficeGroup, #officeGroup, #sectorGroup, #departmentGroup').hide();
        }
    }

    $('#complaint_type').on('change', function () {
        applyComplaintTypeToggle($(this).val());
    });

    // Run on load (handles edit mode too)
    applyComplaintTypeToggle($('#complaint_type').val());




});
