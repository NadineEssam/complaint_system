@extends('dashboard.layouts.app')

@section('title', 'عرض الخدمة')

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

    .section-card {
        background: #fff;
        border: 1px solid #eef1f4;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 24px;
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

    .info-row {
        margin-bottom: 20px;
    }

    .info-label {
        font-weight: 700;
        color: #6c757d;
        margin-bottom: 8px;
        display: block;
    }

    .info-value {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 14px 16px;
        min-height: 50px;
        color: #212529;
        border: 1px solid #edf0f2;
        line-height: 1.8;
    }

    .btn-back {
        border-radius: 14px;
        padding: 11px 30px;
        font-weight: 700;
    }

    @media(max-width:768px) {
        .custom-card-header {
            padding: 20px;
        }

        .section-card {
            padding: 18px;
        }

        .btn-back {
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
                            عرض الخدمة
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('services.index') }}"
                                class="text-secondary">
                                <i class="bx bx-server"></i>
                                الخدمات
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
                    <i class="bx bx-show-alt"></i>
                    عرض الخدمة
                </h4>
                <p>
                    يمكنك الإطلاع على تفاصيل الخدمة.
                </p>
                <div class="info-badge">
                    <i class="bx bx-hash"></i>
                    رقم الخدمة:
                    <strong>#{{ $service->srevicetyptid }}</strong>
                </div>
            </div>

            <div class="card-body p-lg-4 p-3">

                {{-- البيانات الأساسية --}}
                <div class="section-card">

                    <div class="section-title">
                        <i class="bx bx-info-circle"></i>
                        البيانات الأساسية
                    </div>

                    <div class="row">

                        {{-- اسم الخدمة --}}
                        <div class="col-12 info-row">
                            <label class="info-label">
                                اسم الخدمة
                            </label>
                            <div class="info-value">
                                {{ $service->srevicetyptname ?? '-' }}
                            </div>
                        </div>
                        {{-- الحالة --}}
                        <div class="col-md-6 info-row">
                            <label class="info-label">الحالة</label>
                            <div class="info-value">
                                @if($service->validity == 1)
                                <span class="badge bg-success px-3 py-2" style="font-size:13px;">فعّال</span>
                                @else
                                <span class="badge bg-danger px-3 py-2" style="font-size:13px;">غير فعّال</span>
                                @endif
                            </div>
                        </div>

                        {{-- تاريخ الإنشاء --}}
                        <div class="col-md-6 info-row">
                            <label class="info-label">
                                تاريخ الإنشاء
                            </label>
                            <div class="info-value">
                                {{ $service->created_at ? $service->created_at->format('Y-m-d H:i') : '-' }}
                            </div>
                        </div>

                        {{-- أنشأ بواسطة --}}
                        <div class="col-md-6 info-row">
                            <label class="info-label">
                                أنشأ بواسطة
                            </label>
                            <div class="info-value">
                                {{ $service->createdBy->userID ?? '-' }}
                            </div>
                        </div>

                        {{-- آخر تعديل --}}
                        <div class="col-md-6 info-row">
                            <label class="info-label">
                                آخر تعديل في
                            </label>
                            <div class="info-value">
                                {{ $service->updated_at ? $service->updated_at->format('Y-m-d H:i') : '-' }}
                            </div>
                        </div>

                        {{-- آخر تعديل بواسطة --}}
                        <div class="col-md-6 info-row">
                            <label class="info-label">
                                آخر تعديل بواسطة
                            </label>
                            <div class="info-value">
                                {{ $service->updatedBy->userID ?? '-' }}
                            </div>
                        </div>

                    </div>

                </div>

                {{-- buttons --}}
                <div class="text-center mt-4">

                    @if(PerUser('services.edit'))
                    <a href="{{ route('services.edit', $service->srevicetyptid) }}"
                        class="btn btn-primary btn-back shadow-sm">
                        <i class="bx bx-edit-alt ml-1"></i>
                        تعديل الخدمة
                    </a>
                    @endif

                    <a href="{{ route('services.index') }}"
                        class="btn btn-secondary btn-back shadow-sm ms-2">
                        <i class="bx bx-arrow-back ml-1"></i>
                        العودة للخدمات
                    </a>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection