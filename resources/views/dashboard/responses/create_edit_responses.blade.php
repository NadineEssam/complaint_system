@extends('dashboard.layouts.app')

@section('title', isset($response) ? 'تعديل الرد على البيان' : 'إضافة رد البيان')

@push('headScripts')
<style>
    .page-breadcrumb .breadcrumb {
        background: transparent;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        display: inline-block;
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

    .custom-card-header {
        padding: 24px 30px;
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: #fff;
    }

    .custom-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 22px;
    }

    .custom-card-header p {
        margin: 8px 0 0;
        opacity: .9;
        font-size: 14px;
    }

    .section-card {
        background: #fff;
        border: 1px solid #eef1f4;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 24px;
        transition: .3s;
    }

    .section-card:hover {
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        color: #0d6efd;
        font-size: 18px;
        font-weight: 700;
    }

    .section-title i {
        font-size: 22px;
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
        padding: 12px 15px;
        transition: .2s;
        box-shadow: none !important;
    }

    textarea.form-control {
        min-height: 130px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
    }

    .required-star {
        color: #dc3545;
    }

    .btn-save {
        border-radius: 14px;
        padding: 12px 40px;
        font-weight: 700;
        font-size: 15px;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.18);
        padding: 7px 14px;
        border-radius: 50px;
        margin-top: 14px;
        font-size: 13px;
    }

    .disabled-box {
        opacity: .6;
        transition: .3s;
    }

    .invalid-feedback {
        font-size: 13px;
        margin-top: 6px;
    }

    @media(max-width:768px) {

        .custom-card-header {
            padding: 20px;
        }

        .section-card {
            padding: 18px;
        }

        .btn-save {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- breadcrumb --}}
        <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">

            <div class="pr-3">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb mb-0 p-0 shadow-none">

                    <li class="breadcrumb-item active text-primary font-weight-bold">
                            {{ isset($response) ? 'تعديل الرد على البيان' : 'إضافة رد للبيان' }}
                        </li>

                    <li class="breadcrumb-item">
                            <a href="{{ route('responses.index', ['complaint_id' => $complaint->ComplaintID]) }}"
                                class="text-secondary">
                                <i class="bx bx-message-square-detail"></i>
                                الردود
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('complaints.index') }}">
                                الشكاوى
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-secondary">
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
            <div class="custom-card-header">

                <h4>
                    <i class="bx bx-message-rounded-detail"></i>

                    {{ isset($response) ? 'تعديل رد البيان' : 'إضافة رد جديد' }}
                </h4>

                <p>
                    يمكنك تحديث حالة البيان وإضافة تفاصيل الرد بسهولة.
                </p>

                <div class="info-badge">
                    <i class="bx bx-hash"></i>
                    رقم البيان:
                    <strong>#{{ $complaint->ComplaintID }}</strong>
                </div>

            </div>

            <div class="card-body p-lg-4 p-3">

                <form method="POST"
                    action="{{ isset($response) ? route('responses.update', $response->id) : route('responses.store') }}">

                    @csrf

                    @if(isset($response))
                    @method('PUT')
                    @endif

                    <input type="hidden" name="complaint_id" value="{{ $complaint->ComplaintID }}">

                    {{-- البيانات الأساسية --}}
                    <div class="section-card">

                        <div class="section-title">
                            <i class="bx bx-info-circle"></i>
                            البيانات الأساسية
                        </div>

                        <div class="row">

                            {{-- الحالة --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    حالة الطلب
                                    <span class="required-star">*</span>
                                </label>

                                <select class="form-control @error('ComplaintStatus') is-invalid @enderror"
                                    name="ComplaintStatus"
                                    id="statusSelect"
                                    required>

                                    <option value="">اختر الحالة</option>

                                    @foreach ($statuses as $status)

                                    <option value="{{ $status->statusID }}"
                                        {{ old('ComplaintStatus', $response->ComplaintStatus ?? '') == $status->statusID ? 'selected' : '' }}>

                                        {{ $status->statusText }}

                                    </option>

                                    @endforeach

                                </select>

                                @error('ComplaintStatus')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>

                            {{-- نوع الخدمة --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    نوع الخدمة
                                </label>

                                <select class="form-control" name="ComplaintService">

                                    <option value="">اختر نوع الخدمة</option>

                                    @foreach ($serviceTypes as $service)

                                    <option value="{{ $service->srevicetyptid }}"
                                        {{ old('ComplaintService', $response->ComplaintService ?? '') == $service->srevicetyptid ? 'selected' : '' }}>

                                        {{ $service->srevicetyptname }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- تفاصيل الحالة --}}
                            <div class="col-12 mb-2">

                                <label class="form-label">
                                    تفاصيل الحالة
                                </label>

                                <textarea
                                    class="form-control @error('ComplaintText') is-invalid @enderror"
                                    name="ComplaintText"
                                    placeholder="أدخل تفاصيل الحالة أو الرد على البيان...">{{ old('ComplaintText', $response->ComplaintText ?? '') }}</textarea>

                                @error('ComplaintText')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                    {{-- أسباب الإغلاق --}}
                    <div class="section-card" id="closeReasonBox">

                        <div class="section-title">
                            <i class="bx bx-category-alt"></i>
                            بيانات الإغلاق والتصنيف
                        </div>

                        <div class="row">

                            {{-- سبب البيان --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    سبب البيان
                                </label>

                                <select class="form-control"
                                    name="fk_close_reason_id"
                                    id="reasonSelect">

                                    <option value="">اختر السبب</option>

                                    @foreach ($closeReasons as $reason)

                                    <option value="{{ $reason->close_reason_ID }}"
                                        {{ old('fk_close_reason_id', $response->fk_close_reason_id ?? '') == $reason->close_reason_ID ? 'selected' : '' }}>

                                        {{ $reason->close_reason_Name }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- التصنيف --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    التصنيف
                                </label>

                                <select class="form-control"
                                    name="fk_close_reason_classify_id"
                                    id="classifySelect">

                                    <option value="">اختر التصنيف</option>

                                    @foreach ($classifications as $classify)
                                    <option
                                        value="{{ $classify->close_reason_classify_id }}"
                                        data-reason="{{ $classify->fk_close_reason_id }}"
                                        {{ old('fk_close_reason_classify_id', $response->fk_close_reason_classify_id ?? '') == $classify->close_reason_classify_id ? 'selected' : '' }}>
                                        {{ $classify->close_reason_classify_Name }}
                                    </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                    {{-- submit --}}
                    <div class="text-center mt-4">

                        <button type="submit" class="btn btn-primary btn-save shadow-sm">

                            <i class="bx bx-save ml-1"></i>

                            {{ isset($response) ? 'تحديث الرد' : 'حفظ الرد' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@endsection

@push('footerScripts')
<script>
    function toggleFields() {

        let status = $('#statusSelect').val();

        if (status == 2 || status == 4) {

            $('#reasonSelect').prop('disabled', false);
            $('#classifySelect').prop('disabled', false);

            $('#closeReasonBox').removeClass('disabled-box');

        } else {

            $('#reasonSelect').prop('disabled', true).val('');
            $('#classifySelect').prop('disabled', true).val('');

            $('#closeReasonBox').addClass('disabled-box');
        }
    }
    function filterClassifications() {

    let reasonId = $('#reasonSelect').val();

    $('#classifySelect option').each(function () {

        let optionReason = $(this).data('reason');

        if ($(this).val() === '') {
            $(this).show();
            return;
        }

        if (reasonId == optionReason) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    // إعادة تعيين التصنيف إذا لم يعد متوافقاً
    let selectedOption = $('#classifySelect option:selected');

    if (
        selectedOption.val() &&
        selectedOption.data('reason') != reasonId
    ) {
        $('#classifySelect').val('');
    }
}

    $(document).ready(function() {

    toggleFields();
    filterClassifications();

    $('#statusSelect').on('change', function() {
        toggleFields();
    });

    $('#reasonSelect').on('change', function() {
        filterClassifications();
    });

});
</script>
@endpush