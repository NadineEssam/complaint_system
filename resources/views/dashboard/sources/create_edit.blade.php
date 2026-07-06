@extends('dashboard.layouts.app')

@section('title', isset($source) ? 'تعديل المصدر' : 'إضافة مصدر جديد')

@push('headScripts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>

  /* ================= GLOBAL ================= */
  .classify-form, .classify-form * {
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

  .classify-form .breadcrumb-item + .breadcrumb-item::before {
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

  /* ================= SECTION CARD ================= */
  .classify-form .section-card {
    border: 1px solid #eef0f3;
    border-radius: 12px;
    padding: 20px;
    background: #fff;
    margin-bottom: 20px;
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
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
    border-radius: 8px;
    min-height: 42px;
    border: 1px solid #e5e7eb;
    box-shadow: none !important;
    transition: border-color .15s;
    color: #1f2937;
  }

  .classify-form .form-control:focus,
  .classify-form .form-select:focus {
    border-color: #3b76e0;
    box-shadow: 0 0 0 3px rgba(59, 118, 224, 0.1) !important;
  }

  .classify-form .form-select {
    background-position: left 0.75rem center !important;
    padding-left: 2.25rem !important;
    padding-right: 0.75rem !important;
  }

  .classify-form .is-invalid {
    border-color: #d3849a !important;
  }

  .classify-form .error-text {
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
    .classify-form { padding: 15px; }
    .classify-form .btn-custom { width: 100%; justify-content: center; }
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
          {{ isset($source) ? 'تعديل المصدر' : 'إضافة مصدر جديد' }}
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('sources.index') }}">المصادر</a>
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
        <i class="bx bx-git-branch"></i>
      </div>
      <div>
        <h4>{{ isset($source) ? 'تعديل المصدر' : 'إضافة مصدر جديد' }}</h4>
        <p>يمكنك {{ isset($source) ? 'تحديث' : 'إضافة' }} بيانات مصدر البيان بسهولة.</p>
      </div>
    </div>

    <div class="card-body p-lg-4 p-3">

      <form method="POST"
        action="{{ isset($source) ? route('sources.update', $source->comsourcesid) : route('sources.store') }}">

        @csrf
        @if(isset($source))
          @method('PUT')
        @endif

        {{-- ================= SECTION ================= --}}
        <div class="section-card">

          <div class="section-title">
            <i class="bx bx-info-circle"></i>
            البيانات الأساسية
          </div>

          <div class="row">

            {{-- اسم المصدر --}}
            <div class="col-12 mb-4">
              <label class="form-label">
                اسم المصدر <span class="required-star">*</span>
              </label>
              <input type="text"
                class="form-control @error('comsourcesname') is-invalid @enderror"
                name="comsourcesname"
                placeholder="أدخل اسم المصدر (مثال: البريد الإلكتروني، الهاتف)..."
                value="{{ old('comsourcesname', $source->comsourcesname ?? '') }}"
                required>
              @error('comsourcesname')
              <div class="error-text">{{ $message }}</div>
              @enderror
            </div>

            {{-- الحالة --}}
            <div class="col-12 mb-4">
              <label class="form-label">الحالة</label>
              <select class="form-select @error('validity') is-invalid @enderror" name="validity">
                <option value="1" {{ old('validity', $source->validity ?? 1) == 1 ? 'selected' : '' }}>فعّال</option>
                <option value="0" {{ old('validity', $source->validity ?? 1) == 0 ? 'selected' : '' }}>غير فعّال</option>
              </select>
              @error('validity')
              <div class="error-text">{{ $message }}</div>
              @enderror
            </div>

          </div>

          {{-- Buttons --}}
          <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('sources.index') }}" class="btn btn-secondary btn-custom">
              <i class="bx bx-arrow-back me-1"></i> رجوع
            </a>
            <button type="submit" class="btn btn-success btn-custom">
              <i class="bx bx-save me-1"></i>
              {{ isset($source) ? 'تحديث المصدر' : 'حفظ المصدر' }}
            </button>
          </div>

        </div>

      </form>
    </div>
  </div>

</div>

@endsection