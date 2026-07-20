@extends('dashboard.layouts.app')

@section('title', isset($response) ? 'تعديل الرد على البيان' : 'إضافة رد البيان')

@push('headScripts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  /* ================= GLOBAL ================= */
  .classify-form,
  .classify-form * {
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
  }

  .classify-form {
    background: #f7f8fa;
    padding: 24px;
    border-radius: 16px;
  }

  /* ================= BREADCRUMB ================= */
  .classify-form .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
  }

  .classify-form .breadcrumb-item,
  .classify-form .breadcrumb-item a {
    font-size: 13px;
    color: #98a2b3;
    text-decoration: none;
  }

  .classify-form .breadcrumb-item.active {
    color: #1f2937;
    font-weight: 700;
  }

  .classify-form .breadcrumb-item+.breadcrumb-item::before {
    content: "/";
    color: #d1d5db;
    padding: 0 6px;
  }

  /* ================= MAIN CARD ================= */
  .classify-form .main-card {
    border: 1px solid #eef0f3 !important;
    border-radius: 14px !important;
    background: #fff;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }

  /* ================= CARD HEADER ================= */
  .classify-form .form-header {
    background: #fff;
    border-bottom: 1px solid #eef0f3;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .classify-form .form-header .header-icon {
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

  .classify-form .form-header h4 {
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .classify-form .form-header p {
    font-size: 13px;
    color: #9ca3af;
    margin: 2px 0 0;
  }

  /* ================= INFO BADGE ================= */
  .classify-form .info-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eaf2ff;
    color: #3b76e0;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-inline-start: auto;
  }

  /* ================= SECTION CARD ================= */
  .classify-form .section-card {
    border: 1px solid #eef0f3;
    border-radius: 12px;
    padding: 20px;
    background: #fff;
    margin-bottom: 20px;
    transition: opacity .2s;
  }

  .classify-form .section-card.disabled-box {
    opacity: .55;
  }

  .classify-form .section-title {
    font-size: 13px;
    font-weight: 700;
    color: #3b76e0;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .classify-form .section-title i {
    font-size: 16px;
  }

  /* ================= FORM CONTROLS ================= */
  .classify-form .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
  }

  .classify-form .form-control,
  .classify-form .form-select {
    background-position: left 0.75rem center !important;
    padding-left: 2.25rem;
    padding-right: 0.75rem;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
    border-radius: 8px;
    min-height: 42px;
    border: 1px solid #e5e7eb;
    box-shadow: none !important;
    transition: border-color .15s;
    color: #1f2937;
  }
  .classify-form .select2-selection__arrow {
  left: 8px !important;
  right: auto !important;
}

  .classify-form textarea.form-control {
    min-height: 110px;
    resize: vertical;
  }

  .classify-form .form-control:focus,
  .classify-form .form-select:focus {
    border-color: #3b76e0;
    box-shadow: 0 0 0 3px rgba(59, 118, 224, 0.1) !important;
  }

  .classify-form .is-invalid {
    border-color: #d3849a !important;
  }

  .classify-form .error-text,
  .classify-form .invalid-feedback {
    color: #d3556a;
    font-size: 13px;
    margin-top: 4px;
  }

  .classify-form .required-star {
    color: #d3556a;
  }

  /* ================= BUTTONS ================= */
  .classify-form .btn-custom {
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

  .classify-form .btn-primary.btn-custom {
    background: #3b76e0;
    border-color: #3b76e0;
  }

  .classify-form .btn-primary.btn-custom:hover {
    background: #2e65cc;
    border-color: #2e65cc;
  }

  .classify-form .btn-secondary.btn-custom {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #374151;
  }

  .classify-form .btn-secondary.btn-custom:hover {
    background: #e9eaec;
  }

  .classify-form .btn-success.btn-custom {
    background: #eafaf0;
    border-color: #c6edd8;
    color: #2f9e63;
  }

  .classify-form .btn-success.btn-custom:hover {
    background: #d4f5e4;
    color: #1f7a4b;
  }

  /* ================= RESPONSIVE ================= */
  @media(max-width:768px) {
    .classify-form {
      padding: 15px;
    }

    .classify-form .btn-custom {
      width: 100%;
      justify-content: center;
    }

    .classify-form .info-badge {
      margin-inline-start: 0;
    }
  }
</style>
@endpush

@section('content')

<div class="classify-form" dir="rtl">

  {{-- ================= BREADCRUMB ================= --}}
  <div class="mb-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          {{ isset($response) ? 'تعديل الرد على البيان' : 'إضافة رد للبيان' }}
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('responses.index', ['complaint_id' => $complaint->ComplaintID]) }}">
            <i class="bx bx-message-square-detail"></i> الردود
          </a>
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
        <i class="bx bx-message-rounded-detail"></i>
      </div>
      <div>
        <h4>{{ isset($response) ? 'تعديل رد البيان' : 'إضافة رد جديد' }}</h4>
        <p>يمكنك تحديث حالة البيان وإضافة تفاصيل الرد بسهولة.</p>
      </div>
      <div class="info-badge">
        <i class="bx bx-hash"></i>
        رقم البيان: <strong>#{{ $complaint->ComplaintID }}</strong>
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

        {{-- ================= BASIC INFO ================= --}}
        <div class="section-card">

          <div class="section-title">
            <i class="bx bx-info-circle"></i>
            البيانات الأساسية
          </div>

          <div class="row">

            {{-- حالة الطلب --}}
            <div class="col-md-6 mb-4">
              <label class="form-label">
                حالة الطلب <span class="required-star">*</span>
              </label>
              <select class="form-select @error('ComplaintStatus') is-invalid @enderror"
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
              <div class="error-text">{{ $message }}</div>
              @enderror
            </div>

            {{-- نوع الخدمة --}}
            <div class="col-md-6 mb-4">
              <label class="form-label">نوع الخدمة</label>
              <select class="form-select" name="ComplaintService">
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
              <label class="form-label">تفاصيل الحالة</label>
              <textarea
                class="form-control @error('ComplaintText') is-invalid @enderror"
                name="ComplaintText"
                placeholder="أدخل تفاصيل الحالة أو الرد على البيان...">{{ old('ComplaintText', $response->ComplaintText ?? '') }}</textarea>
              @error('ComplaintText')
              <div class="error-text">{{ $message }}</div>
              @enderror
            </div>

          </div>

        </div>

        {{-- ================= CLOSE REASON / CLASSIFY ================= --}}
        <div class="section-card" id="closeReasonBox">

          <div class="section-title">
            <i class="bx bx-category-alt"></i>
            بيانات الإغلاق والتصنيف
          </div>

          <div class="row">

            {{-- سبب البيان --}}
            <div class="col-md-6 mb-4">
              <label class="form-label">سبب البيان</label>
              <select class="form-select @error('fk_close_reason_id') is-invalid @enderror"
                name="fk_close_reason_id" id="reasonSelect">
                <option value="">اختر السبب</option>
                @foreach ($closeReasons as $reason)
                <option value="{{ $reason->close_reason_ID }}"
                  {{ old('fk_close_reason_id', $response->fk_close_reason_id ?? '') == $reason->close_reason_ID ? 'selected' : '' }}>
                  {{ $reason->close_reason_Name }}
                </option>
                @endforeach
              </select>
              @error('fk_close_reason_id')
              <div class="error-text">{{ $message }}</div>
              @enderror
            </div>

            {{-- التصنيف --}}
            <div class="col-md-6 mb-4">
              <label class="form-label">التصنيف</label>
              <select class="form-select @error('fk_close_reason_classify_id') is-invalid @enderror"
                name="fk_close_reason_classify_id" id="classifySelect">
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
              @error('fk_close_reason_classify_id')
              <div class="error-text">{{ $message }}</div>
              @enderror
            </div>

          </div>



        </div>
        <div>
          {{-- Buttons --}}
          <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('responses.index', ['complaint_id' => $complaint->ComplaintID]) }}" class="btn btn-secondary btn-custom">
              <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
            <button type="submit" class="btn btn-success btn-custom">
              <i class="bx bx-save me-1"></i>
              {{ isset($response) ? 'تحديث الرد' : 'حفظ الرد' }}
            </button>
          </div>
        </div>
      </form>
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

    $('#classifySelect option').each(function() {

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

  $(document).ready(function() {

    toggleFields();
    filterClassifications();

    $('#statusSelect').on('change', function() {
        toggleFields();
    });

    $('#reasonSelect').on('change', function() {
        filterClassifications();
    });

   
    $('form').on('submit', function (e) {
        let status = $('#statusSelect').val();
        let reason = $('#reasonSelect').val();
        let classify = $('#classifySelect').val();

        if ((status == 2 || status == 4) && (!reason || !classify)) {
            e.preventDefault();

            $('#reasonSelect').toggleClass('is-invalid', !reason);
            $('#classifySelect').toggleClass('is-invalid', !classify);

            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يجب اختيار "سبب البيان" و "التصنيف" عند إغلاق البيان',
                confirmButtonText: 'حسناً'
            });
        }
    });

});
</script>

@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'error',
        title: 'تنبيه',
        text: 'يجب اختيار "سبب البيان" و "التصنيف" عند اختيار حالة إغلاق البيان',
        confirmButtonText: 'حسناً'
    });
});
</script>
@endif
@endpush