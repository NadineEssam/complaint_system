@extends('dashboard.layouts.app')

@section('title', 'عرض الرد على البيان')

@push('headScripts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>

  /* ================= GLOBAL ================= */
  .classify-show, .classify-show * {
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
  }

  .classify-show {
    background: #f7f8fa;
    padding: 24px;
    border-radius: 16px;
  }

  /* ================= BREADCRUMB ================= */
  .classify-show .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
  }

  .classify-show .breadcrumb-item,
  .classify-show .breadcrumb-item a {
    font-size: 13px;
    color: #98a2b3;
    text-decoration: none;
  }

  .classify-show .breadcrumb-item.active {
    color: #1f2937;
    font-weight: 700;
  }

  .classify-show .breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: #d1d5db;
    padding: 0 6px;
  }

  /* ================= MAIN CARD ================= */
  .classify-show .main-card {
    border: 1px solid #eef0f3 !important;
    border-radius: 14px !important;
    background: #fff;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }

  /* ================= CARD HEADER ================= */
  .classify-show .form-header {
    background: #fff;
    border-bottom: 1px solid #eef0f3;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .classify-show .form-header .header-icon {
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

  .classify-show .form-header h4 {
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .classify-show .form-header p {
    font-size: 13px;
    color: #9ca3af;
    margin: 2px 0 0;
  }

  /* ================= INFO BADGE ================= */
  .classify-show .info-badge {
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

  /* ================= SECTION CARDS ================= */
  .classify-show .detail-card {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 16px;
  }

  .classify-show .detail-section-title {
    font-size: 13px;
    font-weight: 700;
    color: #3b76e0;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 7px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eef0f3;
  }

  .classify-show .detail-section-title i {
    font-size: 16px;
  }

  /* ================= DETAIL GRID ================= */
  .classify-show .detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 12px;
  }

  .classify-show .detail-row.full {
    grid-template-columns: 1fr;
  }

  .classify-show .detail-item {
    padding: 12px 14px;
    background: #f7f8fa;
    border-radius: 8px;
    border-right: 3px solid #3b76e0;
  }

  .classify-show .detail-label {
    font-size: 13px;
    font-weight: 700;
    color: #6b7280;
    margin-bottom: 5px;
    display: block;
  }

  .classify-show .detail-value {
    font-size: 13px;
    color: #1f2937;
    font-weight: 600;
    line-height: 1.5;
    word-break: break-word;
  }

  .classify-show .detail-value.text-block {
    min-height: 100px;
    font-weight: 500;
    white-space: pre-line;
  }

  /* ================= STATUS BADGE ================= */
  .classify-show .status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    background: #eaf2ff;
    color: #3b76e0;
  }

  /* ================= BUTTONS ================= */
  .classify-show .action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    flex-wrap: wrap;
    margin-bottom: 16px;
  }

  .classify-show .btn-custom {
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    min-width: 110px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }

  .classify-show .btn-secondary.btn-custom {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #374151;
  }

  .classify-show .btn-secondary.btn-custom:hover {
    background: #e9eaec;
  }

  /* ================= RESPONSIVE ================= */
  @media(max-width:768px) {
    .classify-show { padding: 15px; }
    .classify-show .action-buttons { flex-direction: column; }
    .classify-show .btn-custom { width: 100%; justify-content: center; }
    .classify-show .detail-row { grid-template-columns: 1fr; }
    .classify-show .info-badge { margin-inline-start: 0; }
  }

</style>
@endpush

@section('content')

<div class="classify-show" dir="rtl">

  {{-- ================= BREADCRUMB ================= --}}
  <div class="mb-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          عرض الرد
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('responses.index', ['complaint_id' => $response->complaint_id]) }}">
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
        <i class="bx bx-show-alt"></i>
      </div>
      <div>
        <h4>عرض الرد على البيان</h4>
        <p>يمكنك الإطلاع على تفاصيل الرد المسجل على البيان.</p>
      </div>
      <div class="info-badge">
        <i class="bx bx-hash"></i>
        رقم البيان: <strong>#{{ $response->complaint_id }}</strong>
      </div>
    </div>

    <div class="card-body p-lg-4 p-3">

      {{-- ================= ACTION BUTTONS ================= --}}
      <div class="action-buttons">
        <a href="{{ route('responses.index', ['complaint_id' => $response->complaint_id]) }}" class="btn btn-secondary btn-custom">
          <i class="bx bx-arrow-back"></i> العودة للردود
        </a>
      </div>

      {{-- ================= BASIC INFO ================= --}}
      <div class="detail-card">
        <div class="detail-section-title">
          <i class="bx bx-info-circle"></i>
          البيانات الأساسية
        </div>
        <div class="detail-row">

          <div class="detail-item">
            <span class="detail-label">حالة الطلب</span>
            <span class="status-badge">{{ $response->status->statusText ?? '-' }}</span>
          </div>

          <div class="detail-item">
            <span class="detail-label">نوع الخدمة</span>
            <span class="detail-value">{{ $response->serviceType->srevicetyptname ?? '-' }}</span>
          </div>

          <div class="detail-item">
            <span class="detail-label">تاريخ الرد</span>
            <span class="detail-value">
              {{ $response->created_at ? $response->created_at->format('Y-m-d H:i') : '-' }}
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">سبب البيان</span>
            <span class="detail-value">{{ $response->closeReason->close_reason_Name ?? '-' }}</span>
          </div>

          <div class="detail-item">
            <span class="detail-label">التصنيف</span>
            <span class="detail-value">{{ $response->classification->close_reason_classify_Name ?? '-' }}</span>
          </div>

        </div>
      </div>

      {{-- ================= RESPONSE DETAILS ================= --}}
      <div class="detail-card">
        <div class="detail-section-title">
          <i class="bx bx-message-detail"></i>
          تفاصيل الرد
        </div>
        <div class="detail-row full">
          <div class="detail-item">
            <span class="detail-value text-block">{{ $response->ComplaintText ?? '-' }}</span>
          </div>
        </div>
      </div>

      {{-- ================= SYSTEM INFO ================= --}}
      <div class="detail-card">
        <div class="detail-section-title">
          <i class="bx bx-cog"></i>
          معلومات النظام
        </div>
        <div class="detail-row">

          <div class="detail-item">
            <span class="detail-label">تم الإضافة بواسطة</span>
            <span class="detail-value">{{ $response->createdBy->userID ?? '-' }}</span>
          </div>

          <div class="detail-item">
            <span class="detail-label">آخر تعديل بواسطة</span>
            <span class="detail-value">{{ $response->updatedBy->userID ?? '-' }}</span>
          </div>

        </div>
      </div>

    </div>
  </div>

</div>

@endsection