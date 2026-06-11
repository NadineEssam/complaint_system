@extends('dashboard.layouts.app')

@section('title', isset($serviceType) ? 'تعديل نوع الخدمة' : 'إضافة نوع الخدمة')

@push('headScripts')
<style>
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

    .form-label {
        font-weight: 700;
        color: #495057;
        margin-bottom: 10px;
    }

    .form-control {
        border-radius: 12px;
        min-height: 50px;
        border: 1px solid #dce1e7;
        padding: 12px 15px;
        transition: .2s;
        box-shadow: none !important;
    }

    .form-control:focus {
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

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
            <div class="pr-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 shadow-none">
                        <li class="breadcrumb-item active text-primary font-weight-bold">
                            {{ isset($serviceType) ? 'تعديل نوع الخدمة' : 'إضافة نوع الخدمة' }}
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('service-types.index') }}" class="text-secondary">
                                <i class="bx bx-service"></i>
                                أنواع الخدمات
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

        <div class="card border-0 shadow-sm" dir="rtl">

            <!-- Header -->
            <div class="custom-card-header">
                <h4>
                    <i class="bx bx-service"></i>
                    {{ isset($serviceType) ? 'تعديل نوع الخدمة' : 'إضافة نوع الخدمة جديد' }}
                </h4>
                <p>
                    {{ isset($serviceType) ? 'قم بتحديث بيانات نوع الخدمة' : 'أضف نوع خدمة جديد إلى النظام' }}
                </p>
            </div>

            <div class="card-body p-lg-4 p-3">

                <form method="POST"
                    action="{{ isset($serviceType) ? route('service-types.update', $serviceType->srevicetyptid) : route('service-types.store') }}">

                    @csrf

                    @if(isset($serviceType))
                    @method('PUT')
                    @endif

                    <div class="section-card">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    اسم الخدمة
                                    <span class="required-star">*</span>
                                </label>

                                <input type="text" class="form-control @error('srevicetyptname') is-invalid @enderror"
                                    name="srevicetyptname"
                                    value="{{ old('srevicetyptname', $serviceType->srevicetyptname ?? '') }}"
                                    placeholder="أدخل اسم الخدمة"
                                    required>

                                @error('srevicetyptname')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-save shadow-sm">
                            <i class="bx bx-save ml-1"></i>
                            {{ isset($serviceType) ? 'تحديث' : 'حفظ' }}
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@endsection