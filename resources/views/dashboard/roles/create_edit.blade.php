@extends('dashboard.layouts.app')
@push('headScripts')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ================= GLOBAL ================= */
        .roles-page, .roles-page * {
            font-family: 'Cairo', 'Tahoma', sans-serif;
            font-size: 13px;
        }

        .roles-page {
            background: #f7f8fa;
            padding: 24px;
            border-radius: 16px;
        }

        /* ================= BREADCRUMB ================= */
        .roles-page .breadcrumb {
            margin-bottom: 0;
            background: transparent;
            padding: 0;
        }

        .roles-page .breadcrumb-item,
        .roles-page .breadcrumb-item a {
            font-size: 13px;
            color: #98a2b3;
            text-decoration: none;
        }

        .roles-page .breadcrumb-item.active {
            color: #1f2937;
            font-weight: 700;
        }

        /* ================= CARD ================= */
        .roles-page .main-card {
            border: 1px solid #eef0f3 !important;
            border-radius: 14px !important;
            background: #fff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        /* ================= CARD HEADER ================= */
        .roles-page .card-top-header {
            padding: 16px 20px;
            border-bottom: 1px solid #eef0f3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }

        .roles-page .card-top-header h4 {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .roles-page .card-top-header h4 i {
            color: #3b76e0;
            font-size: 16px;
        }

        /* ================= FORM ================= */
        .roles-page .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
        }

        .roles-page .form-control,
        .roles-page .form-select {
            font-size: 13px;
            font-family: 'Cairo', 'Tahoma', sans-serif;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            color: #1f2937;
            padding: 8px 12px;
            background-color: #fff;
            box-shadow: none !important;
            transition: border-color .15s;
        }

        .roles-page .form-control:focus,
        .roles-page .form-select:focus {
            border-color: #3b76e0;
            box-shadow: 0 0 0 0.18rem rgba(59,118,224,.13) !important;
        }

        /* ================= SAVE BUTTON ================= */
        .roles-page .btn-save {
            font-size: 13px;
            font-weight: 600;
            padding: 8px 22px;
            border-radius: 8px;
            background: #eafaf0;
            color: #2f9e63;
            border: 1px solid #c6edd8;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background .15s;
        }

        .roles-page .btn-save:hover {
            background: #d4f5e4;
            color: #1f7a4b;
        }
    </style>
@endpush
@section('content')

<div class="roles-page" dir="rtl">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    {{ isset($role) ? 'تعديل الدور: ' . $role->name : 'إضافة دور جديد' }}
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('roles.index') }}">
                        <i class="bx bx-shape-polygon"></i> الأدوار
                    </a>
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

        {{-- Card Header --}}
        <div class="card-top-header">
            <h4>
                <i class="bx bx-shield-quarter"></i>
                {{ isset($role) ? 'تعديل بيانات الدور' : 'بيانات الدور الجديد' }}
            </h4>
        </div>

        {{-- Body --}}
        <div class="p-4">

            <form method="POST"
                action="{{ isset($role) ? route('roles.update', ['role' => $role]) : route('roles.store') }}">
                @if (isset($role))
                    @method('PUT')
                @endif
                @csrf

                <div class="row mb-3">
                    <label for="name" class="col-sm-2 col-form-label form-label">اسم الدور</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" id="name" value="{{ isset($role) ? $role->name : old('name') }}"
                            placeholder="أدخل اسم الدور">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label form-label">الصلاحيات</label>
                    <div class="col-sm-10">
                        @include('dashboard.include.permissions_table')
                    </div>
                </div>

                <div class="row mb-3 mt-4">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn-save">
                            <i class="bx bx-save"></i> حفظ البيانات
                        </button>
                    </div>
                </div>
            </form>

        </div>

    </div>

</div>

@endsection

@push('footerScripts')

@endpush