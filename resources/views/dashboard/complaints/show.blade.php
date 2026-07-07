@extends('dashboard.layouts.app')

@section('title', 'عرض البيان')

@push('headScripts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>

  /* ================= GLOBAL ================= */
  .complaint-show, .complaint-show * {
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
  }

  .complaint-show {
    background: #f7f8fa;
    padding: 24px;
    border-radius: 16px;
  }

  /* ================= BREADCRUMB ================= */
  .complaint-show .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
  }

  .complaint-show .breadcrumb-item,
  .complaint-show .breadcrumb-item a {
    font-size: 13px;
    color: #98a2b3;
    text-decoration: none;
  }

  .complaint-show .breadcrumb-item.active {
    color: #1f2937;
    font-weight: 700;
  }

  .complaint-show .breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: #d1d5db;
    padding: 0 6px;
  }

  /* ================= MAIN CARD ================= */
  .complaint-show .main-card {
    border: 1px solid #eef0f3 !important;
    border-radius: 14px !important;
    background: #fff;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }

  /* ================= CARD HEADER ================= */
  .complaint-show .form-header {
    background: #fff;
    border-bottom: 1px solid #eef0f3;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .complaint-show .form-header .header-icon {
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

  .complaint-show .form-header h4 {
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .complaint-show .form-header p {
    font-size: 13px;
    color: #9ca3af;
    margin: 2px 0 0;
  }

  .complaint-show .duplicate-chip {
    margin-right: auto;
    font-size: 13px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
  }

  /* ================= SECTION CARDS ================= */
  .complaint-show .detail-card {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 16px;
  }

  .complaint-show .detail-section-title {
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

  .complaint-show .detail-section-title i {
    font-size: 16px;
  }

  /* ================= DETAIL GRID ================= */
  .complaint-show .detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 12px;
    margin-bottom: 0;
  }

  .complaint-show .detail-item {
    padding: 12px 14px;
    background: #f7f8fa;
    border-radius: 8px;
    border-right: 3px solid #3b76e0;
  }

  .complaint-show .detail-label {
    font-size: 13px;
    font-weight: 700;
    color: #6b7280;
    margin-bottom: 5px;
    display: block;
    letter-spacing: 0;
    text-transform: none;
  }

  .complaint-show .detail-value {
    font-size: 13px;
    color: #1f2937;
    font-weight: 600;
    line-height: 1.5;
    word-break: break-word;
  }

  /* ================= STATUS BADGE ================= */
  .complaint-show .status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
  }

  .complaint-show .status-badge.pending     { background: #fdf3e6; color: #c3791f; }
  .complaint-show .status-badge.in-progress { background: #eaf2ff; color: #3b76e0; }
  .complaint-show .status-badge.resolved    { background: #eafaf0; color: #2f9e63; }
  .complaint-show .status-badge.closed      { background: #f3f4f6; color: #6b7280; }

  /* ================= SOURCE BADGES ================= */
  .complaint-show .badge-group {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
  }

  .complaint-show .source-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #eaf2ff;
    color: #3b76e0;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
  }

  /* ================= COMPLAINT TEXT ================= */
  .complaint-show .textarea-display {
    background: #f7f8fa;
    border: 1px solid #eef0f3;
    border-radius: 8px;
    padding: 14px;
    line-height: 1.8;
    color: #1f2937;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
  }

  /* ================= TIMELINE ================= */
  .complaint-show .timeline-item {
    display: flex;
    margin-bottom: 16px;
    align-items: flex-start;
    gap: 12px;
  }

  .complaint-show .timeline-icon {
    width: 36px;
    height: 36px;
    background: #eaf2ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b76e0;
    flex-shrink: 0;
    font-size: 15px;
    font-weight: 700;
  }

  .complaint-show .timeline-date {
    color: #9ca3af;
    font-size: 13px;
    font-weight: 600;
  }

  .complaint-show .timeline-text {
    color: #1f2937;
    font-size: 13px;
    margin-top: 3px;
  }

  /* ================= BUTTONS ================= */
  .complaint-show .action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    flex-wrap: wrap;
    margin-bottom: 16px;
  }

  .complaint-show .btn-custom {
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

  .complaint-show .btn-warning.btn-custom {
    background: #fdf3e6;
    border-color: #f0d4b0;
    color: #c3791f;
  }

  .complaint-show .btn-warning.btn-custom:hover {
    background: #fce8cc;
    color: #a3611a;
  }

  .complaint-show .btn-secondary.btn-custom {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #374151;
  }

  .complaint-show .btn-secondary.btn-custom:hover {
    background: #e9eaec;
  }

  .complaint-show .btn-danger.btn-custom {
    background: #fdecee;
    border-color: #f5c6cb;
    color: #d3556a;
  }

  .complaint-show .btn-danger.btn-custom:hover {
    background: #fbd9dd;
    color: #b0394e;
  }

  /* ================= ALERT ================= */
  .complaint-show .alert-warning {
    background: #fdf3e6;
    border: 1px solid #f0d4b0;
    color: #c3791f;
    border-radius: 8px;
    font-size: 13px;
  }

  /* ================= MODAL ================= */
  .complaint-show .modal-content {
    border-radius: 12px;
    border: 1px solid #eef0f3;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
  }

  .complaint-show .modal-header {
    border-bottom: 1px solid #eef0f3;
    padding: 14px 18px;
  }

  .complaint-show .modal-title {
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
  }

  /* ================= RESPONSIVE ================= */
  @media(max-width:768px) {
    .complaint-show { padding: 15px; }

    .complaint-show .action-buttons { flex-direction: column; }

    .complaint-show .btn-custom { width: 100%; justify-content: center; }

    .complaint-show .detail-row { grid-template-columns: 1fr; }
  }

</style>
@endpush

@section('content')

<div class="complaint-show" dir="rtl">

  {{-- ================= BREADCRUMB ================= --}}
  <div class="mb-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          عرض البيان #{{ $complaint->ComplaintID }}
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
    @php
      $isDuplicated    = $complaint->is_duplicated;
      $duplicatesCount = $complaint->duplicates_count;
    @endphp

    <div class="form-header">
      <div class="header-icon">
        <i class="bx bx-file-blank"></i>
      </div>
      <div>
        <h4>تفاصيل البيان #{{ $complaint->ComplaintID }}</h4>
        <p>بيانات شاملة للبيان والمتقدم بها</p>
      </div>

      <span class="badge duplicate-chip {{ $isDuplicated ? 'bg-warning text-dark' : 'bg-light text-dark border' }}"
        style="cursor: {{ $isDuplicated ? 'pointer' : 'default' }};"
        @if($isDuplicated)
          data-bs-toggle="modal"
          data-bs-target="#duplicatesModal"
          id="duplicateChip"
        @endif>
        @if($isDuplicated)
          مكرر ({{ $duplicatesCount }})
        @else
          غير مكرر
        @endif
      </span>
    </div>

    <div class="card-body p-lg-4 p-3">

      {{-- ================= ACTION BUTTONS ================= --}}
      <div class="action-buttons">

        <!-- @if (PerUser('complaints.edit'))
        <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-warning btn-custom">
          <i class="bx bx-edit-alt"></i> تعديل
        </a>
        @endif

        @if (PerUser('complaints.destroy'))
        <button class="btn btn-danger btn-custom delete-complaint"
          data-id="{{ $complaint->id }}"
          data-url="{{ route('complaints.destroy', $complaint) }}">
          <i class="bx bx-trash"></i> حذف
        </button>
        @endif -->

        @php $openMember = $complaint->open_family_member; @endphp

        @if ($openMember)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
          <i class="bx bx-error-circle fs-5"></i>
          <span>البيان رقم #{{ $openMember->ComplaintID }} لم يُقفل بعد، لا يمكن إضافة تكرار حتى يتم إغلاق جميع البيانات المرتبطة.</span>
        </div>
        @elseif (PerUser('complaints.duplicate'))
        <a href="{{ route('complaints.duplicate.create', $complaint) }}" class="btn btn-warning btn-custom">
          <i class="bx bx-copy-alt"></i> اضافه تكرار
        </a>
        @endif

        <a href="{{ route('complaints.index') }}" class="btn btn-secondary btn-custom">
          <i class="bx bx-arrow-back"></i> رجوع
        </a>

      </div>

      {{-- ================= STATUS SECTION ================= --}}
      <div class="detail-card">
        <div class="detail-section-title">
          <i class="bx bx-info-circle"></i>
          حالة البيان
        </div>
        <div class="detail-row">
          <div class="detail-item">
            <span class="detail-label">الحالة الحالية</span>
            <span class="status-badge {{ strtolower(str_replace(' ', '-', $complaint->status->statusText ?? '')) }}">
              {{ $complaint->status->statusText ?? 'غير محدد' }}
            </span>
          </div>
          <div class="detail-item">
            <span class="detail-label">رقم البيان</span>
            <span class="detail-value">#{{ $complaint->ComplaintID }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">تاريخ الإنشاء</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($complaint->ComplaintDate)->format('d/m/Y') }}</span>
          </div>
        </div>
      </div>

      {{-- ================= PERSONAL INFO ================= --}}
      <div class="detail-card">
        <div class="detail-section-title">
          <i class="bx bx-user"></i>
          البيانات الشخصية
        </div>
        <div class="detail-row">
          <div class="detail-item">
            <span class="detail-label">اسم الشاكي</span>
            <span class="detail-value">{{ $complaint->ComplainerName }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">الرقم القومي</span>
            <span class="detail-value">{{ $complaint->ComplaintNationalID ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">النوع</span>
            <span class="detail-value">{{ $complaint->ComplainerGender ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">محافظة مقدم البيان</span>
            <span class="detail-value">{{ $complaint->complainerGov->GOVT_NAMA ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">رقم الهاتف</span>
            <span class="detail-value">{{ $complaint->ComplainerPhone }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">البريد الإلكتروني</span>
            <span class="detail-value">{{ $complaint->ComplainerEmail ?? 'غير محدد' }}</span>
          </div>
        </div>
      </div>

      {{-- ================= COMPLAINT DETAILS ================= --}}
      <div class="detail-card">
        <div class="detail-section-title">
          <i class="bx bx-detail"></i>
          تفاصيل البيان
        </div>
        <div class="detail-row">
          <div class="detail-item">
            <span class="detail-label">نوع الطلب</span>
            <span class="detail-value">{{ $complaint->requestType->requesttypename ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">نوع النشاط</span>
            <span class="detail-value">{{ $complaint->projectTypes->sector_nama ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">نوعية وتوجيه البيان</span>
            <span class="detail-value">
              {{ $complaint->complaint_type === 'internal' ? 'داخلي' : ($complaint->complaint_type === 'external' ? 'خارجي' : 'غير محدد') }}
            </span>
          </div>

          @if($complaint->complaint_type === 'external')
          <div class="detail-item">
            <span class="detail-label">المحافظة</span>
            <span class="detail-value">{{ $complaint->gov->GOVT_NAMA ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">الفرع</span>
            <span class="detail-value">{{ $complaint->office_info->REG_OFFIC_NAMA ?? 'غير محدد' }}</span>
          </div>
          @endif

          @if($complaint->complaint_type === 'internal')
          <div class="detail-item">
            <span class="detail-label">القطاع</span>
            <span class="detail-value">{{ $complaint->sector->sector_ar ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">الإدارة</span>
            <span class="detail-value">{{ $complaint->departmentInfo->depname_ar ?? 'غير محدد' }}</span>
          </div>
          @endif
        </div>

        @if($complaint->sources->count() > 0)
        <div style="margin-top:16px; padding-top:14px; border-top:1px solid #eef0f3;">
          <span class="detail-label">مصادر البيان</span>
          <div class="badge-group">
            @foreach($complaint->sources as $source)
            <span class="source-badge">
              <i class="bx bx-check"></i>
              {{ $source->comsourcesname }}
            </span>
            @endforeach
          </div>
        </div>
        @endif
      </div>

      {{-- ================= COMPLAINT TEXT ================= --}}
      <div class="detail-card">
        <div class="detail-section-title">
          <i class="bx bx-message-square-dots"></i>
          نص البيان
        </div>
        <div class="textarea-display">
          {{ $complaint->ComplaintText }}
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
            <span class="detail-label">موظف الإدخال</span>
            <span class="detail-value">{{ $complaint->username ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">تاريخ الإدخال</span>
            <span class="detail-value">{{ $complaint->entryDate?->format('d/m/Y H:i') ?? 'غير محدد' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">موظف التعديل</span>
            <span class="detail-value">{{ $complaint->UpdateUser ?? 'لم يتم التعديل' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">تاريخ التعديل</span>
            <span class="detail-value">{{ $complaint->UpdateDate?->format('d/m/Y H:i') ?? 'لم يتم التعديل' }}</span>
          </div>
        </div>
      </div>

      {{-- ================= DUPLICATES MODAL ================= --}}
      <div class="modal fade" id="duplicatesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" dir="rtl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">تكرارات البيان رقم #{{ $complaint->ComplaintID }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="duplicatesModalBody">
              <div class="text-center py-4">
                <i class="bx bx-loader-alt bx-spin fs-3"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

@endsection

@push('footerScripts')
<script src="{{ asset('assets/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.delete-complaint').on('click', function(e) {
            e.preventDefault();
            let url = $(this).attr('data-url');

            Swal.fire({
                title: 'هل أنت متأكد من عملية الحذف؟',
                text: "لن تتمكن من التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: { '_token': '{{ csrf_token() }}' },
                        success: function(msg) {
                            Swal.fire(
                                'تم الحذف!',
                                msg.message,
                                msg.success ? 'success' : 'error'
                            ).then(() => {
                                window.location.href = '{{ route("complaints.index") }}';
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<script>
    $('#duplicatesModal').on('show.bs.modal', function() {
        var body = $('#duplicatesModalBody');
        body.html('<div class="text-center py-4"><i class="bx bx-loader-alt bx-spin fs-3"></i></div>');

        if (!$.fn.DataTable) {
            $.getScript('https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js', function() {
                loadDuplicatesTable(body);
            });
        } else {
            loadDuplicatesTable(body);
        }
    });

    function loadDuplicatesTable(body) {
        $.get("{{ route('complaints.duplicates.index', $complaint) }}", function(html) {
            body.html(html);
        }).fail(function() {
            body.html('<div class="text-center text-danger py-4">حدث خطأ أثناء تحميل البيانات</div>');
        });
    }
</script>
@endpush