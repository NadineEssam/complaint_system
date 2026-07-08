@extends('dashboard.layouts.app')

@section('title', isset($complaint) ? 'تعديل البيان' : 'إضافة البيان')

@push('headScripts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>

  /* ================= GLOBAL ================= */
  .complaints-form, .complaints-form * {
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
  }

  .complaints-form {
    background: #f7f8fa;
    padding: 24px;
    border-radius: 16px;
  }

  /* ================= BREADCRUMB ================= */
  .complaints-form .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
  }

  .complaints-form .breadcrumb-item,
  .complaints-form .breadcrumb-item a {
    font-size: 13px;
    color: #98a2b3;
    text-decoration: none;
  }

  .complaints-form .breadcrumb-item.active {
    color: #1f2937;
    font-weight: 700;
  }

  .complaints-form .breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: #d1d5db;
    padding: 0 6px;
  }

  /* ================= MAIN CARD ================= */
  .complaints-form .main-card {
    border: 1px solid #eef0f3 !important;
    border-radius: 14px !important;
    background: #fff;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }

  /* ================= CARD HEADER ================= */
  .complaints-form .form-header {
    background: #fff;
    border-bottom: 1px solid #eef0f3;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .complaints-form .form-header .header-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 10px;
    background: #eaf2ff;
    color: #3b76e0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }

  .complaints-form .form-header h4 {
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .complaints-form .form-header p {
    font-size: 13px;
    color: #9ca3af;
    margin: 2px 0 0;
  }

  /* ================= STEP WIZARD ================= */
  .complaints-form .stepwizard {
    width: 100%;
    position: relative;
    margin-bottom: 28px;
  }

  .complaints-form .stepwizard-row {
    display: flex;
    justify-content: space-between;
    position: relative;
  }

  .complaints-form .stepwizard-row::before {
    content: "";
    position: absolute;
    top: 18px;
    right: 10%;
    width: 80%;
    height: 2px;
    background: #eef0f3;
    z-index: 0;
    border-radius: 50px;
  }

  .complaints-form .stepwizard-step {
    position: relative;
    z-index: 1;
    text-align: center;
    width: 100%;
  }

  .complaints-form .stepwizard-step p {
    margin-top: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #9ca3af;
  }

  .complaints-form .btn-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 2px solid #e5e7eb;
    background: #fff;
    color: #9ca3af;
    font-weight: 700;
    font-size: 13px;
    transition: .2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
  }

  .complaints-form .btn-circle.active {
    background: #3b76e0;
    border-color: #3b76e0;
    color: #fff;
    box-shadow: 0 4px 12px rgba(59, 118, 224, 0.25);
  }

  /* ================= SECTION CARD ================= */
  .complaints-form .section-card {
    border: 1px solid #eef0f3;
    border-radius: 12px;
    padding: 20px;
    background: #fff;
    margin-bottom: 20px;
  }

  .complaints-form .section-title {
    font-size: 13px;
    font-weight: 700;
    color: #3b76e0;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .complaints-form .section-title i {
    font-size: 16px;
  }

  /* ================= FORM CONTROLS ================= */
  .complaints-form .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
  }

  .complaints-form .form-control,
  .complaints-form .form-select {
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
    border-radius: 8px;
    min-height: 42px;
    border: 1px solid #e5e7eb;
    box-shadow: none !important;
    transition: border-color .15s;
    color: #1f2937;
  }

  .complaints-form .form-control:focus,
  .complaints-form .form-select:focus {
    border-color: #3b76e0;
    box-shadow: 0 0 0 3px rgba(59, 118, 224, 0.1) !important;
  }

  .complaints-form textarea.form-control {
    min-height: 110px;
    resize: vertical;
  }

  .complaints-form .form-select {
    background-position: left 0.75rem center !important;
    padding-left: 2.25rem !important;
    padding-right: 0.75rem !important;
  }

  .complaints-form .is-invalid {
    border-color: #d3849a !important;
  }

  .complaints-form .error-text {
    color: #d3556a;
    font-size: 13px;
    margin-top: 4px;
  }

  .complaints-form .required-star {
    color: #d3556a;
  }

  /* ================= STEP CONTENT ================= */
  .complaints-form .setup-content {
    display: none;
    animation: fadeIn .25s ease;
  }

  .complaints-form .setup-content.active {
    display: block;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ================= BUTTONS ================= */
  .complaints-form .btn-custom {
    min-width: 120px;
    border-radius: 8px;
    padding: 9px 18px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }

  .complaints-form .btn-primary.btn-custom {
    background: #3b76e0;
    border-color: #3b76e0;
  }

  .complaints-form .btn-primary.btn-custom:hover {
    background: #2e65cc;
    border-color: #2e65cc;
  }

  .complaints-form .btn-secondary.btn-custom {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #374151;
  }

  .complaints-form .btn-secondary.btn-custom:hover {
    background: #e9eaec;
  }

  .complaints-form .btn-success.btn-custom {
    background: #eafaf0;
    border-color: #c6edd8;
    color: #2f9e63;
  }

  .complaints-form .btn-success.btn-custom:hover {
    background: #d4f5e4;
    color: #1f7a4b;
  }

  /* ================= SELECT2 ================= */
  .complaints-form .select2-container--bootstrap-5 .select2-selection {
    min-height: 42px !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 8px !important;
    background: #fff !important;
    box-shadow: none !important;
    padding: 4px 10px !important;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px !important;
  }

  .complaints-form .select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #3b76e0 !important;
    box-shadow: 0 0 0 3px rgba(59, 118, 224, 0.1) !important;
  }

  .complaints-form .select2-container--bootstrap-5 .select2-selection__choice {
    border-radius: 6px !important;
    padding: 3px 8px !important;
    margin: 2px !important;
    font-size: 13px !important;
    background: #eaf2ff !important;
    border-color: #c7dbf9 !important;
    color: #3b76e0 !important;
  }

  .complaints-form .select2-container--bootstrap-5 .select2-selection__rendered {
    display: flex !important;
    flex-wrap: wrap;
    align-items: center;
    gap: 3px;
  }

  .complaints-form .select2-container--bootstrap-5 .select2-search--inline .select2-search__field {
    margin-top: 0 !important;
    height: 28px !important;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px !important;
  }

  .complaints-form .select2-container--bootstrap-5 .select2-dropdown {
    border-radius: 10px !important;
    border: 1px solid #e5e7eb !important;
    overflow: hidden;
    font-size: 13px;
    font-family: 'Cairo', 'Tahoma', sans-serif;
  }

  .complaints-form .select2-container--bootstrap-5 .select2-results__option {
    padding: 9px 14px;
    font-size: 13px;
  }

  .complaints-form .select2-container {
    width: 100% !important;
  }

  .complaints-form .select2-container--bootstrap-5 .select2-selection--multiple {
    padding-right: 10px !important;
    padding-left: 35px !important;
    background-position: left 0.75rem center !important;
  }

  /* ================= RESPONSIVE ================= */
  @media(max-width:768px) {
    .complaints-form { padding: 15px; }

    .complaints-form .stepwizard-row::before { display: none; }

    .complaints-form .btn-custom { width: 100%; justify-content: center; }

    .complaints-form .d-flex.gap-mobile {
      flex-direction: column;
      gap: 10px;
    }
  }

</style>
@endpush

@php
$step = (
  $errors->has('ComplaintDate') ||
  $errors->has('ComplaintGovernorate') ||
  $errors->has('sec_id') ||
  $errors->has('office') ||
  $errors->has('ComplaintText') ||
  $errors->has('comsource_id') ||
  $errors->has('ComplaintProjectType')
) ? 2 : 1;
@endphp

@section('content')

<div class="complaints-form" dir="rtl">

  {{-- ================= BREADCRUMB ================= --}}
  <div class="mb-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          {{ isset($complaint) ? 'تعديل البيان' : 'إضافة بيان' }}
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('complaints.index') }}">الشكاوى</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('dashboard') }}">
            <i class="bx bx-home-alt"></i> الرئيسية
          </a>
        </li>
      </ol>
    </nav>
  </div>

  {{-- ================= MAIN CARD ================= --}}
  <div class="main-card">

    {{-- Header --}}
    <div class="form-header">
      <div class="header-icon">
        <i class="bx bx-message-square-add"></i>
      </div>
      <div>
        <h4>{{ isset($complaint) ? 'تعديل البيان' : 'إضافة بيان جديدة' }}</h4>
        <p>قم بإدخال بيانات العميل وبيانات البيان بشكل صحيح لإتمام الحفظ.</p>
      </div>
    </div>

    <div class="card-body p-lg-4 p-3">

      {{-- ================= STEP WIZARD ================= --}}
      <div class="stepwizard">
        <div class="stepwizard-row setup-panel">

          <div class="stepwizard-step">
            <a href="#step-1" class="btn-circle active step-link">1</a>
            <p>البيانات الشخصية</p>
          </div>

          <div class="stepwizard-step">
            <a href="#step-2" class="btn-circle step-link">2</a>
            <p>تفاصيل البيان</p>
          </div>

        </div>
      </div>

      <form method="POST" novalidate
        action="{{ ($isDuplicateMode ?? false) ? route('complaints.duplicate.store', $parentComplaint) : (isset($complaint) ? route('complaints.update', $complaint) : route('complaints.store')) }}">

        @csrf

        @if(isset($complaint) && !($isDuplicateMode ?? false))
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
                  نوع الطلب <span class="required-star">*</span>
                </label>
                <select class="form-select @error('requesttypeid') is-invalid @enderror"
                  name="requesttypeid" id="requesttypeid">
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

              {{-- النوع - readonly --}}
              <div class="col-md-6 mb-4" id="genderReadonlySection">
                <label class="form-label">النوع</label>
                <input type="text"
                  class="form-control"
                  name="ComplainerGender"
                  id="ComplainerGenderReadonly"
                  value="{{ old('ComplainerGender', $complaint->ComplainerGender ?? '') }}"
                  readonly>
              </div>

              {{-- النوع - dropdown --}}
              <div class="col-md-6 mb-4" id="genderSelectSection" style="display:none;">
                <label class="form-label">
                  النوع <span class="required-star">*</span>
                </label>
                <select class="form-select" name="ComplainerGenderSelect" id="ComplainerGenderSelect">
                  <option value="">اختر النوع</option>
                  <option value="ذكر"  {{ old('ComplainerGender', $complaint->ComplainerGender ?? '') == 'ذكر'  ? 'selected' : '' }}>ذكر</option>
                  <option value="أنثى" {{ old('ComplainerGender', $complaint->ComplainerGender ?? '') == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                </select>
                <div class="error-text" id="genderError"></div>
              </div>

              {{-- الاسم --}}
              <div class="col-md-6 mb-4">
                <label class="form-label">
                  اسم العميل <span class="required-star">*</span>
                </label>
                <input type="text"
                  class="form-control @error('ComplainerName') is-invalid @enderror"
                  name="ComplainerName"
                  value="{{ old('ComplainerName', $complaint->ComplainerName ?? '') }}">
                @error('ComplainerName')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              {{-- محافظة مقدم البيان --}}
              <div class="col-md-6 mb-4">
                <label class="form-label">
                  محافظة مقدم البيان <span class="required-star">*</span>
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

              {{-- الهاتف --}}
              <div class="col-md-6 mb-4">
                <label class="form-label">
                  رقم الهاتف المحمول <span class="required-star">*</span>
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
                  <span id="emailStar" style="display:none;color:#d3556a;">*</span>
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

            <div class="text-end mt-2">
              <button type="button" class="btn btn-primary btn-custom nextBtn">
                التالي <i class="bx bx-left-arrow-alt ms-1"></i>
              </button>
            </div>

          </div>
        </div>

        {{-- ================= STEP 2 ================= --}}
        <div class="setup-content" id="step-2">
          <div class="section-card">

            <div class="section-title">
              <i class="bx bx-detail"></i>
              تفاصيل البيان
            </div>

            <div class="row">

              {{-- التاريخ --}}
              <div class="col-md-6 mb-4">
                <label class="form-label">
                  التاريخ <span class="required-star">*</span>
                </label>
                <input type="date"
                  dir="rtl" lang="ar"
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
                </label>
                <select class="form-select @error('ComplaintProjectType') is-invalid @enderror"
                  name="ComplaintProjectType" id="ComplaintProjectType">
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

              {{-- نوعية وتوجيه البيان --}}
              <div class="col-md-6 mb-4">
                <label class="form-label">
                  نوعية وتوجيه البيان <span class="required-star">*</span>
                </label>
                <select class="form-select @error('complaint_type') is-invalid @enderror"
                  name="complaint_type" id="complaint_type">
                  <option value="">اختر</option>
                  <option value="external" {{ old('complaint_type', $complaint->complaint_type ?? '') == 'external' ? 'selected' : '' }}>خارجي</option>
                  <option value="internal" {{ old('complaint_type', $complaint->complaint_type ?? '') == 'internal' ? 'selected' : '' }}>داخلي</option>
                </select>
                @error('complaint_type')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              {{-- القطاع --}}
              <div class="col-md-6 mb-4" id="sectorGroup" style="display:none;">
                <label class="form-label">
                  القطاع <span class="required-star">*</span>
                </label>
                <select class="form-select @error('sec_id') is-invalid @enderror" name="sec_id">
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
                  الإدارة <span class="required-star">*</span>
                </label>
                <select class="form-select @error('department') is-invalid @enderror"
                  name="department" id="department">
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
                  المحافظة <span class="required-star">*</span>
                </label>
                <select class="form-select @error('ComplaintGovernorate') is-invalid @enderror"
                  id="governorateSelect" name="ComplaintGovernorate">
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

              {{-- الفرع --}}
              <div class="col-md-6 mb-4" id="officeGroup" style="display:none;">
                <label class="form-label">
                  الفرع <span class="required-star">*</span>
                </label>
                <select class="form-select @error('office') is-invalid @enderror"
                  id="officeSelect" name="office">
                  <option value="">اختر الفرع</option>
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

              {{-- مصدر البيان --}}
              <div class="col-md-6 mb-4">
                <label class="form-label">
                  مصدر البيان <span class="required-star">*</span>
                </label>
                <select class="form-select select2-multi @error('comsource_ids') is-invalid @enderror"
                  name="comsource_ids[]"
                  id="comsourceSelect"
                  multiple
                  style="width:100%;">
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
                  نص البيان <span class="required-star">*</span>
                </label>
                <textarea
                  class="form-control @error('ComplaintText') is-invalid @enderror"
                  name="ComplaintText"
                  id="ComplaintText"
                  rows="5"
                  placeholder="أدخل تفاصيل البيان هنا...">{{ old('ComplaintText', $complaint->ComplaintText ?? '') }}</textarea>
                @error('ComplaintText')
                <div class="error-text">{{ $message }}</div>
                @enderror
                <div class="error-text" id="complaintTextError"></div>
              </div>

            </div>

            <div class="d-flex justify-content-between gap-mobile mt-3">

              <button type="button" class="btn btn-secondary btn-custom prevBtn">
                <i class="bx bx-right-arrow-alt me-1"></i> رجوع
              </button>

              <button type="submit" class="btn btn-success btn-custom">
                <i class="bx bx-save me-1"></i> حفظ البيان
              </button>

            </div>

          </div>
        </div>

      </form>
    </div>
  </div>

</div>

@endsection

@push('footerScripts')
<script>
    window.currentWizardStep = "{{ $step == 2 ? 'step-2' : 'step-1' }}";
</script>

<script>
    $(document).ready(function() {
        $('#comsourceSelect').select2({
            theme: 'bootstrap-5',
            dir: 'rtl',
            placeholder: 'اختر مصادر البيان',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

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