@extends('dashboard.layouts.app')

@section('title', isset($complaint) ? 'تعديل الشكوى' : 'إضافة شكوى')

@push('headScripts')
<style>
    select.form-select {
        background-position: left 0.75rem center !important;
        padding-left: 2.25rem !important;
        padding-right: 0.75rem !important;
    }

    .page-breadcrumb .breadcrumb {
        background: transparent;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        padding: 0 8px;
        color: #adb5bd;
        content: "/";
    }

    .main-card {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    }

    .custom-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: #fff;
        padding: 28px;
    }

    .custom-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 24px;
    }

    .custom-header p {
        margin: 10px 0 0;
        opacity: .9;
        font-size: 14px;
    }

    .stepwizard {
        width: 100%;
        position: relative;
        margin-bottom: 35px;
    }

    .stepwizard-row {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .stepwizard-row::before {
        content: "";
        position: absolute;
        top: 22px;
        right: 10%;
        width: 80%;
        height: 4px;
        background: #e9ecef;
        z-index: 0;
        border-radius: 50px;
    }

    .stepwizard-step {
        position: relative;
        z-index: 1;
        text-align: center;
        width: 100%;
    }

    .stepwizard-step p {
        margin-top: 12px;
        font-weight: 600;
        color: #6c757d;
    }

    .btn-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 0;
        background: #dee2e6;
        color: #495057;
        font-weight: bold;
        font-size: 16px;
        transition: .3s;
    }

    .btn-circle.active {
        background: #0d6efd;
        color: #fff;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }

    .section-card {
        border: 1px solid #edf0f3;
        border-radius: 18px;
        padding: 25px;
        background: #fff;
        margin-bottom: 25px;
    }

    .section-title {
        color: #0d6efd;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label {
        font-weight: 700;
        color: #495057;
        margin-bottom: 10px;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        min-height: 50px;
        border: 1px solid #dce1e7;
        box-shadow: none !important;
        transition: .3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.08) !important;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .error-text {
        color: #dc3545;
        font-size: 13px;
        margin-top: 6px;
    }

    .setup-content {
        display: none;
        animation: fadeIn .3s ease;
    }

    .setup-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-custom {
        min-width: 140px;
        border-radius: 12px;
        padding: 11px 20px;
        font-weight: 700;
    }

    .required-star {
        color: red;
    }

    @media(max-width:768px) {

        .custom-header {
            padding: 20px;
        }

        .section-card {
            padding: 18px;
        }

        .stepwizard-row::before {
            display: none;
        }

        .btn-custom {
            width: 100%;
        }

        .d-flex.gap-mobile {
            flex-direction: column;
            gap: 10px;
        }
    }

    /* Select2 Multi Select Design */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 50px !important;
        border: 1px solid #dce1e7 !important;
        border-radius: 12px !important;
        background: #fff !important;
        box-shadow: none !important;
        padding: 6px 10px !important;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.08) !important;
    }

    .select2-container--bootstrap-5 .select2-selection__choice {
        border-radius: 8px !important;
        padding: 4px 10px !important;
        margin: 3px !important;
        font-size: 13px !important;
    }

    .select2-container--bootstrap-5 .select2-selection__rendered {
        display: flex !important;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
    }

    .select2-container--bootstrap-5 .select2-search--inline .select2-search__field {
        margin-top: 0 !important;
        height: 30px !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border-radius: 12px !important;
        border: 1px solid #dce1e7 !important;
        overflow: hidden;
    }

    .select2-container--bootstrap-5 .select2-results__option {
        padding: 10px 15px;
    }

    /* Validation */
    .select2-container--bootstrap-5 .select2-selection.is-invalid,
    .is-invalid+.select2-container .select2-selection {
        border-color: #dc3545 !important;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple {
        padding-right: 12px !important;
        padding-left: 35px !important;
        background-position: left 0.75rem center !important;
    }
</style>
@endpush
@php
$step = (
$errors->has('ComplaintDate') ||
$errors->has('ComplaintGovernorate') ||
$errors->has('sec_id') ||
$errors->has('office') ||
$errors->has('ComplaintText')||
$errors->has('comsource_id')||
    $errors->has('ComplaintProjectType')
) ? 2 : 1;
@endphp
@section('content')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">

            <div class="pr-3">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb mb-0 p-0 shadow-none">



                        <li class="breadcrumb-item active text-primary font-weight-bold">
                            {{ isset($complaint) ? 'تعديل الشكوى' : 'إضافة شكوى' }}
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('complaints.index') }}">
                                الشكاوى
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="bx bx-home-alt"></i>
                                الرئيسية
                            </a>
                        </li>
                    </ol>

                </nav>

            </div>

        </div>

        <div class="card main-card" dir="rtl">

            {{-- Header --}}
            <div class="custom-header">

                <h4>
                    <i class="bx bx-message-square-add"></i>

                    {{ isset($complaint) ? 'تعديل الشكوى' : 'إضافة شكوى جديدة' }}
                </h4>

                <p>
                    قم بإدخال بيانات العميل وبيانات الشكوى بشكل صحيح لإتمام الحفظ.
                </p>

            </div>

            <div class="card-body p-lg-4 p-3">

                {{-- Step Wizard --}}
                <div class="stepwizard">

                    <div class="stepwizard-row setup-panel">

                        <div class="stepwizard-step">

                            <a href="#step-1" class="btn btn-circle active step-link">
                                1
                            </a>

                            <p>البيانات الشخصية</p>

                        </div>

                        <div class="stepwizard-step">

                            <a href="#step-2" class="btn btn-circle step-link">
                                2
                            </a>

                            <p>تفاصيل الشكوى</p>

                        </div>

                    </div>

                </div>

                <form method="POST" novalidate
                    action="{{ isset($complaint) ? route('complaints.update', $complaint) : route('complaints.store') }}">

                    @csrf

                    @if(isset($complaint))
                    @method('PUT')
                    @endif

                    {{-- ================= STEP 1 ================= --}}
                    <div class="setup-content active" id="step-1">

                        <div class="section-card">

                            <div class="section-title">
                                <i class="bx bx-user"></i>
                                البيانات الشخصية
                            </div>

                            <div class="row">

                                {{-- نوع الطلب --}}
                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        نوع الطلب
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select @error('requesttypeid') is-invalid @enderror"
                                        name="requesttypeid"
                                        id="requesttypeid">

                                        <option value="">اختر</option>

                                        @foreach($requestTypes as $type)

                                        <option value="{{ $type->requesttypeid }}"
                                            {{ old('requesttypeid', $complaint->RequestType ?? '') == $type->requesttypeid ? 'selected' : '' }}>

                                            {{ $type->requesttypename }}

                                        </option>

                                        @endforeach

                                    </select>

                                    @error('requesttypeid')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror

                                </div>

                                {{-- الرقم القومي --}}
                                <div class="col-md-6 mb-4" id="nationalIdSection">

                                    <label class="form-label">
                                        الرقم القومي
                                        <span class="required-star" id="nidStar" style="display:none;">*</span>
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="ComplaintNationalID"
                                        id="ComplaintNationalID"
                                        value="{{ old('ComplaintNationalID', $complaint->ComplaintNationalID ?? '') }}"
                                        autocomplete="off">

                                    <div class="error-text" id="nidError"></div>

                                </div>

                                {{-- النوع - readonly (requesttypeid = 2, auto from NID) --}}
                                <div class="col-md-6 mb-4" id="genderReadonlySection">
                                    <label class="form-label">النوع</label>
                                    <input type="text"
                                        class="form-control"
                                        name="ComplainerGender"
                                        id="ComplainerGenderReadonly"
                                        value="{{ old('ComplainerGender', $complaint->ComplainerGender ?? '') }}"
                                        readonly>
                                </div>

                                {{-- النوع - dropdown (requesttypeid = 1, required) --}}
                                <div class="col-md-6 mb-4" id="genderSelectSection" style="display:none;">
                                    <label class="form-label">
                                        النوع
                                        <span class="required-star">*</span>
                                    </label>
                                    <select class="form-select" name="ComplainerGenderSelect" id="ComplainerGenderSelect">
                                        <option value="">اختر النوع</option>
                                        <option value="ذكر" {{ old('ComplainerGender', $complaint->ComplainerGender ?? '') == 'ذكر'  ? 'selected' : '' }}>ذكر</option>
                                        <option value="أنثى" {{ old('ComplainerGender', $complaint->ComplainerGender ?? '') == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                    <div class="error-text" id="genderError"></div>
                                </div>

                                {{-- الاسم --}}
                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        اسم العميل
                                        <span class="required-star">*</span>
                                    </label>

                                    <input type="text"
                                        class="form-control @error('ComplainerName') is-invalid @enderror"
                                        name="ComplainerName"
                                        value="{{ old('ComplainerName', $complaint->ComplainerName ?? '') }}">

                                    @error('ComplainerName')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        محافظة مقدم الشكوى
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select @error('ComplainerGovernorate') is-invalid @enderror"
                                        name="ComplainerGovernorate">

                                        <option value="">اختر المحافظة</option>

                                        @foreach ($govs as $gov)
                                        <option value="{{ $gov->GOVT_CODE }}"
                                            {{ old('ComplainerGovernorate', $complaint->ComplainerGovernorate ?? '') == $gov->GOVT_CODE ? 'selected' : '' }}>
                                            {{ $gov->GOVT_NAMA }}
                                        </option>
                                        @endforeach

                                    </select>

                                    @error('ComplainerGovernorate')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror

                                    <div class="error-text" id="complainerGovError"></div>
                                </div>

                                {{-- الهاتف المحمول --}}
                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        رقم الهاتف المحمول
                                        <span class="required-star">*</span>
                                    </label>

                                    <input type="text"
                                        class="form-control @error('ComplainerPhone') is-invalid @enderror"
                                        name="ComplainerPhone"
                                        value="{{ old('ComplainerPhone', $complaint->ComplainerPhone ?? '') }}">

                                    @error('ComplainerPhone')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror

                                </div>

                                {{-- البريد --}}
                                <div class="col-md-12 mb-4">

                                    <label class="form-label">
                                        البريد الإلكتروني
                                        <span id="emailStar" style="display:none;color:red">*</span>
                                    </label>

                                    <input type="email"
                                        class="form-control @error('ComplainerEmail') is-invalid @enderror"
                                        name="ComplainerEmail"
                                        value="{{ old('ComplainerEmail', $complaint->ComplainerEmail ?? '') }}">

                                    @error('ComplainerEmail')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror

                                </div>

                                

                            </div>

                            <div class="text-end mt-3">

                                <button type="button" class="btn btn-primary btn-custom nextBtn">
                                    التالي
                                    <i class="bx bx-left-arrow-alt ms-1"></i>
                                </button>

                            </div>

                        </div>

                    </div>

                    {{-- ================= STEP 2 ================= --}}
                    <div class="setup-content" id="step-2">

                        <div class="section-card">

                            <div class="section-title">
                                <i class="bx bx-detail"></i>
                                تفاصيل الشكوى
                            </div>

                            <div class="row">

                                {{-- التاريخ --}}
                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        التاريخ
                                        <span class="required-star">*</span>
                                    </label>

                                    <input type="date"
                                        dir="rtl"
                                        lang="ar"
                                        class="form-control @error('ComplaintDate') is-invalid @enderror"
                                        name="ComplaintDate"
                                        max="{{ date('Y-m-d') }}"
                                        value="{{ old('ComplaintDate', $complaint->ComplaintDate ?? '') }}">

                                    @error('ComplaintDate')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror

                                </div>

                                {{-- نوع النشاط --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        نوع النشاط
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select @error('ComplaintProjectType') is-invalid @enderror"
                                        name="ComplaintProjectType"
                                        id="ComplaintProjectType">

                                        <option value="">اختر</option>

                                        @foreach($projectTypes as $projectType)
                                        
                                        <option value="{{ $projectType->ID }}"
                                            {{ old('ComplaintProjectType', $complaint->ComplaintProjectType ?? '') == $projectType->ID ? 'selected' : '' }}>
                                            {{ $projectType->sector_nama }}
                                        </option>
                                        @endforeach

                                    </select>

                                    @error('ComplaintProjectType')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- نوعية وتوجيه البيان--}}
                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        نوعية وتوجيه البيان
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select @error('complaint_type') is-invalid @enderror"
                                        name="complaint_type"
                                        id="complaint_type">

                                        <option value="">اختر</option>

                                        <option value="external"
                                            {{ old('complaint_type', $complaint->complaint_type ?? '') == 'external' ? 'selected' : '' }}>
                                            خارجي
                                        </option>

                                        <option value="internal"
                                            {{ old('complaint_type', $complaint->complaint_type ?? '') == 'internal' ? 'selected' : '' }}>
                                            داخلي
                                        </option>

                                    </select>

                                    @error('complaint_type')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror

                                </div>

                                {{-- القطاع--}}
                                <div class="col-md-6 mb-4" id="sectorGroup" style="display:none;">
                                    <label class="form-label">القطاع
                                        <span class="required-star">*</span>
                                    </label>
                                    <select class="form-select @error('sec_id') is-invalid @enderror"
                                        name="sec_id">
                                        <option value="">اختر</option>
                                        @foreach($sectors as $sector)

                                        <option value="{{ $sector->sec_id }}"
                                            {{ old('sec_id', $complaint->sector_id ?? '') == $sector->sec_id ? 'selected' : '' }}>

                                            {{ $sector->sector_ar }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('sec_id')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- الإدارة --}}
                                <div class="col-md-6 mb-4" id="departmentGroup" style="display:none;">
                                    <label class="form-label">
                                        الإدارة
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select @error('department') is-invalid @enderror"
                                        name="department"
                                        id="department">

                                        <option value="">اختر الإدارة</option>

                                        @foreach($departments as $department)
                                        <option value="{{ $department->dep_id }}"
                                            data-sector="{{ $department->sector_code }}"
                                            {{ old('department', $complaint->department ?? '') == $department->dep_id ? 'selected' : '' }}>
                                            {{ $department->depname_ar }}
                                        </option>
                                    @endforeach

                                    </select>

                                    @error('department')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- المحافظة --}}
                                <div class="col-md-6 mb-4" id="govOfficeGroup" style="display:none;">

                                    <label class="form-label">
                                        المحافظة
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select @error('ComplaintGovernorate') is-invalid @enderror"
                                        id="governorateSelect"
                                        name="ComplaintGovernorate">

                                        <option value="">اختر المحافظة</option>

                                        @foreach ($govs as $gov)

                                        <option value="{{ $gov->GOVT_CODE }}"
                                            {{ old('ComplaintGovernorate', $complaint->ComplaintGovernorate ?? '') == $gov->GOVT_CODE ? 'selected' : '' }}>

                                            {{ $gov->GOVT_NAMA }}

                                        </option>

                                        @endforeach

                                    </select>

                                    @error('ComplaintGovernorate')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror
                                    <div class="error-text" id="governorateError"></div>

                                </div>

                                {{-- المكتب --}}
                                <div class="col-md-6 mb-4"  id="officeGroup" style="display:none;">

                                    <label class="form-label">
                                        المكتب
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select @error('office') is-invalid @enderror"
                                        id="officeSelect"
                                        name="office">

                                        <option value="">اختر المكتب</option>

                                        @foreach ($offices as $office)

                                        <option value="{{ $office->ID }}"
                                            data-gov="{{ $office->FK_GOVT_CODE }}"
                                            {{ old('office', $complaint->office ?? '') == $office->ID ? 'selected' : '' }}>

                                            {{ $office->REG_OFFIC_NAMA }}

                                        </option>

                                        @endforeach

                                    </select>

                                    @error('office')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror
                                    <div class="error-text" id="officeError"></div>

                                </div>

                                {{-- مصدر الشكوى (MULTI-SELECT) --}}
                                <div class="col-md-12 mb-4">

                                    <label class="form-label">
                                        مصدر الشكوى
                                        <span class="required-star">*</span>
                                    </label>

                                    <select class="form-select select2-multi @error('comsource_ids') is-invalid @enderror"
                                        name="comsource_ids[]"
                                        id="comsourceSelect"
                                        multiple
                                        style="width: 100%;">

                                        <option value="">اختر</option>

                                        @foreach($comsources as $source)

                                        <option value="{{ $source->comsourcesid }}"
                                            @if(isset($complaint) && $complaint->sources->pluck('comsourcesid')->contains($source->comsourcesid))
                                            selected
                                            @endif>

                                            {{ $source->comsourcesname }}

                                        </option>

                                        @endforeach

                                    </select>

                                    @error('comsource_ids')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror
                                    <div class="error-text" id="comsourceError"></div>

                                </div>

                                {{-- نص البيان --}}
                                <div class="col-md-12 mb-4">
                                    <label class="form-label">
                                        نص البيان
                                        <span class="required-star">*</span>
                                    </label>
                                    <textarea
                                        class="form-control @error('ComplaintText') is-invalid @enderror"
                                        name="ComplaintText"
                                        id="ComplaintText"
                                        rows="5"
                                        placeholder="أدخل تفاصيل الشكوى هنا...">{{ old('ComplaintText', $complaint->ComplaintText ?? '') }}</textarea>
                                    @error('ComplaintText')
                                    <div class="error-text">{{ $message }}</div>
                                    @enderror
                                    <div class="error-text" id="complaintTextError"></div>
                                </div>


                            </div>

                            <div class="d-flex justify-content-between gap-mobile mt-4">

                                <button type="button" class="btn btn-secondary btn-custom prevBtn">

                                    <i class="bx bx-right-arrow-alt me-1"></i>

                                    رجوع

                                </button>

                                <button type="submit" class="btn btn-success btn-custom">

                                    <i class="bx bx-save me-1"></i>

                                    حفظ الشكوى

                                </button>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@endsection

@push('footerScripts')
<script>
    window.currentWizardStep = "{{ $step == 2 ? 'step-2' : 'step-1' }}";
</script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for multi-select complaint sources
        $('#comsourceSelect').select2({
            theme: 'bootstrap-5',
            dir: 'rtl',
            placeholder: 'اختر مصادر الشكوى',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

        // Clear error on selection
        $('#comsourceSelect').on('change', function() {
            if ($(this).val() && $(this).val().length > 0) {
                $(this).removeClass('is-invalid');
                $("#comsourceError").text('');
            }
        });
    });
</script>
<script src="{{ asset('assets/js/complaints.js') }}"></script>
<script>
    flatpickr("input[name='ComplaintDate']", {
        locale: "ar",
        dateFormat: "Y-m-d",
        maxDate: "today"
    });
</script>
@endpush