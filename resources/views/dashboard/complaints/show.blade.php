@extends('dashboard.layouts.app')

@section('title', 'عرض الشكوى')

@push('headScripts')
<style>
    .detail-card {
        background: #fff;
        border: 1px solid #edf0f3;
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .detail-section-title {
        color: #0d6efd;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .detail-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
        border-left: 4px solid #0d6efd;
    }

    .detail-label {
        font-weight: 700;
        color: #495057;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .detail-value {
        color: #212529;
        font-size: 15px;
        line-height: 1.6;
        word-break: break-word;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .status-badge.pending {
        background: #ffc107;
        color: #000;
    }

    .status-badge.in-progress {
        background: #17a2b8;
        color: #fff;
    }

    .status-badge.resolved {
        background: #28a745;
        color: #fff;
    }

    .status-badge.closed {
        background: #6c757d;
        color: #fff;
    }

    .badge-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .source-badge {
        display: inline-block;
        background: #e7f3ff;
        color: #0d6efd;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
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

    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-custom {
        border-radius: 12px;
        padding: 11px 20px;
        font-weight: 700;
        min-width: 140px;
    }

    .textarea-display {
        background: #f8f9fa;
        border: 1px solid #dce1e7;
        border-radius: 12px;
        padding: 15px;
        line-height: 1.8;
        color: #212529;
        font-family: inherit;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 20px;
        align-items: flex-start;
        gap: 15px;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        background: #0d6efd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
        font-weight: 700;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-date {
        color: #6c757d;
        font-size: 13px;
        font-weight: 600;
    }

    .timeline-text {
        color: #212529;
        margin-top: 5px;
    }

    @media(max-width:768px) {
        .custom-header {
            padding: 20px;
        }

        .detail-card {
            padding: 18px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-custom {
            width: 100%;
        }

        .detail-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
            <div class="pr-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 shadow-none">
                        <li class="breadcrumb-item active text-primary font-weight-bold">
                            عرض الشكوى #{{ $complaint->ComplaintID }}
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
        </div>

        <div class="card main-card" dir="rtl">

            {{-- Header --}}
            <div class="custom-header">
                <h4>
                    <i class="bx bx-file-blank"></i>
                    تفاصيل الشكوى #{{ $complaint->ComplaintID }}
                </h4>
                <p>بيانات شاملة للشكوى والمتقدم بها</p>
            </div>

            <div class="card-body p-lg-4 p-3">

                {{-- Action Buttons --}}
                <div class="action-buttons mb-4">


                    <!-- @if (PerUser('complaints.edit'))
                    <a href="{{ route('complaints.edit', $complaint) }}" 
                        class="btn btn-warning btn-custom">
                        <i class="bx bx-edit-alt"></i>
                        تعديل
                    </a>
                    @endif

                    @if (PerUser('complaints.destroy'))
                    <button class="btn btn-danger btn-custom delete-complaint" 
                        data-id="{{ $complaint->id }}"
                        data-url="{{ route('complaints.destroy', $complaint) }}">
                        <i class="bx bx-trash"></i>
                        حذف
                    </button>
                    @endif -->

                    <a href="{{ route('complaints.index') }}" class="btn btn-secondary btn-custom">
                        <i class="bx bx-arrow-back"></i>
                        رجوع
                    </a>
                </div>

                {{-- Complaint Status Section --}}
                <div class="detail-card">
                    <div class="detail-section-title">
                        <i class="bx bx-info-circle"></i>
                        حالة الشكوى
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">الحالة الحالية</span>
                            <span class="status-badge {{ strtolower(str_replace(' ', '-', $complaint->status->statusText ?? '')) }}">
                                {{ $complaint->status->statusText ?? 'غير محدد' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">رقم الشكوى</span>
                            <span class="detail-value">#{{ $complaint->ComplaintID }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">تاريخ الإنشاء</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($complaint->ComplaintDate)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Personal Information --}}
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
                            <span class="detail-label">محافظة مقدم الشكوى</span>
                            <span class="detail-value">
                                {{ $complaint->complainerGov->govname ?? 'غير محدد' }}
                            </span>
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

                {{-- Complaint Details --}}
                <div class="detail-card">
                    <div class="detail-section-title">
                        <i class="bx bx-detail"></i>
                        تفاصيل الشكوى
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">نوع الطلب</span>
                            <span class="detail-value">{{ $complaint->requestType->requesttypename ?? 'غير محدد' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">نوع الشكوى</span>
                            <span class="detail-value">{{ $complaint->complaintType->comtypename ?? 'غير محدد' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">القطاع</span>
                            <span class="detail-value">{{ $complaint->sector->sector_name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">المحافظة</span>
                            <span class="detail-value">{{ $complaint->gov->govname ?? 'غير محدد' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">المكتب</span>
                            <span class="detail-value">{{ $complaint->office_info->REG_OFFIC_NAMA ?? 'غير محدد' }}</span>
                        </div>
                    </div>

                    {{-- Complaint Sources --}}
                    @if($complaint->sources->count() > 0)
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                        <span class="detail-label" style="margin-bottom: 12px;">مصادر الشكوى</span>
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

                {{-- Complaint Text --}}
                <div class="detail-card">
                    <div class="detail-section-title">
                        <i class="bx bx-message-square-dots"></i>
                        نص البيان
                    </div>
                    <div class="textarea-display">
                        {{ $complaint->ComplaintText }}
                    </div>
                </div>

                {{-- System Information --}}
                <div class="detail-card">
                    <div class="detail-section-title">
                        <i class="bx bx-cog"></i>
                        معلومات النظام
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">موظف الإدخال</span>
                            <span class="detail-value">{{ $complaint->createdBy->username ?? 'غير محدد' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">تاريخ الإدخال</span>
                            <span class="detail-value">{{ $complaint->created_at?->format('d/m/Y H:i') ?? 'غير محدد' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">موظف التعديل</span>
                            <span class="detail-value">{{ $complaint->updatedBy->username ?? 'لم يتم التعديل' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">تاريخ التعديل</span>
                            <span class="detail-value">{{ $complaint->updated_at?->format('d/m/Y H:i') ?? 'لم يتم التعديل' }}</span>
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
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
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
@endpush