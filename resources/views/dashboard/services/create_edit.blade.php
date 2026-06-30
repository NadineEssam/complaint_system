@extends('dashboard.layouts.app')

@section('title', isset($service) ? 'تعديل الخدمة' : 'إضافة خدمة جديدة')

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
                            {{ isset($service) ? 'تعديل الخدمة' : 'إضافة خدمة جديدة' }}
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
                    <i class="bx bx-server"></i>
                    {{ isset($service) ? 'تعديل الخدمة' : 'إضافة خدمة جديدة' }}
                </h4>
                <p>
                    يمكنك {{ isset($service) ? 'تحديث' : 'إضافة' }} بيانات الخدمة بسهولة.
                </p>
            </div>

            <div class="card-body p-lg-4 p-3">

                <form method="POST"
                    action="{{ isset($service) ? route('services.update', $service->srevicetyptid) : route('services.store') }}">

                    @csrf

                    @if(isset($service))
                    @method('PUT')
                    @endif

                    {{-- البيانات الأساسية --}}
                    <div class="section-card">

                        <div class="section-title">
                            <i class="bx bx-info-circle"></i>
                            البيانات الأساسية
                        </div>

                        <div class="row">

                            {{-- اسم الخدمة --}}
                            <div class="col-12 mb-4">

                                <label class="form-label">
                                    اسم الخدمة
                                    <span class="required-star">*</span>
                                </label>

                                <input type="text"
                                    class="form-control @error('srevicetyptname') is-invalid @enderror"
                                    name="srevicetyptname"
                                    placeholder="أدخل اسم الخدمة..."
                                    value="{{ old('srevicetyptname', $service->srevicetyptname ?? '') }}"
                                    required>

                                @error('srevicetyptname')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                            {{-- الصلاحية --}}
                            <div class="col-12 mb-4">
                                <label class="form-label">
                                    الحالة
                                </label>
                                <select class="form-control @error('validity') is-invalid @enderror" name="validity">
                                    <option value="1" {{ old('validity', $service->validity ?? 1) == 1 ? 'selected' : '' }}>
                                        فعّال
                                    </option>
                                    <option value="0" {{ old('validity', $service->validity ?? 1) == 0 ? 'selected' : '' }}>
                                        غير فعّال
                                    </option>
                                </select>

                                @error('validity')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                    </div>

                    {{-- submit --}}
                    <div class="text-center mt-4">

                        <button type="submit" class="btn btn-primary btn-save shadow-sm">

                            <i class="bx bx-save ml-1"></i>

                            {{ isset($service) ? 'تحديث الخدمة' : 'حفظ الخدمة' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@endsection