@extends('dashboard.layouts.app')

@push('headScripts')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ================= GLOBAL ================= */
        .users-page, .users-page * {
            font-family: 'Cairo', 'Tahoma', sans-serif;
            font-size: 13px;
        }

        .users-page {
            background: #f7f8fa;
            padding: 24px;
            border-radius: 16px;
        }

        /* ================= BREADCRUMB ================= */
        .users-page .breadcrumb {
            margin-bottom: 0;
            background: transparent;
            padding: 0;
        }

        .users-page .breadcrumb-item,
        .users-page .breadcrumb-item a {
            font-size: 13px;
            color: #98a2b3;
            text-decoration: none;
        }

        .users-page .breadcrumb-item.active {
            color: #1f2937;
            font-weight: 700;
        }

        /* ================= CARD ================= */
        .users-page .main-card {
            border: 1px solid #eef0f3 !important;
            border-radius: 14px !important;
            background: #fff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        /* ================= CARD HEADER ================= */
        .users-page .card-top-header {
            padding: 16px 20px;
            border-bottom: 1px solid #eef0f3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }

        .users-page .card-top-header h4 {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .users-page .card-top-header h4 i {
            color: #3b76e0;
            font-size: 16px;
        }

        /* ================= FORM ================= */
        .users-page .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
        }

        .users-page .form-control,
        .users-page .form-select {
            background-position: left 0.75rem center !important;
            padding-left: 2.25rem;
            padding-right: 0.75rem;
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

        .users-page .form-control:focus,
        .users-page .form-select:focus {
            border-color: #3b76e0;
            box-shadow: 0 0 0 0.18rem rgba(59,118,224,.13) !important;
        }

        /* ================= SAVE BUTTON ================= */
        .users-page .btn-save {
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

        .users-page .btn-save:hover {
            background: #d4f5e4;
            color: #1f7a4b;
        }
    </style>
@endpush

@section('content')

<div class="users-page" dir="rtl">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    {{ isset($user) ? 'تعديل المستخدم: ' . $user->userID : 'إضافة مستخدم جديد' }}
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('users.index') }}">
                        <i class="bx bx-user"></i> المستخدمون
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
                <i class="bx bx-user-circle"></i>
                {{ isset($user) ? 'تعديل بيانات المستخدم' : 'بيانات المستخدم الجديد' }}
            </h4>
        </div>

        {{-- Body --}}
        <div class="p-4">

            <form method="POST"
                action="{{ isset($user) ? route('users.update', ['user' => $user]) : route('users.store') }}"
                enctype="multipart/form-data">

                @if (isset($user))
                    @method('PUT')
                @endif

                @csrf

                {{-- اسم المستخدم --}}
                <div class="row mb-4">
                    <label for="userid" class="col-sm-2 col-form-label form-label">
                        اسم المستخدم
                    </label>

                    <div class="col-sm-10">
                        <input type="text"
                            class="form-control @error('userid') is-invalid @enderror"
                            {{ isset($user) ? 'readonly' : '' }}
                            name="userid"
                            id="userid"
                            value="{{ isset($user) ? $user->userID : old('userid') }}"
                            placeholder="أدخل اسم المستخدم">

                        @error('userid')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- الدور والصلاحيات --}}
                <div class="row mb-4">
                    <label for="role_id" class="col-sm-2 col-form-label form-label">
                        الدور والصلاحيات
                    </label>

                    <div class="col-sm-10">

                        <select id="role_id"
                            class="form-select form-select-lg px-4 @error('role_id') is-invalid @enderror"
                            required
                            name="role_id[]">

                            <option value="">
                                اختر الدور والصلاحيات
                            </option>

                            @foreach (\Spatie\Permission\Models\Role::where('guard_name', 'web')->pluck('name', 'id')->toArray() as $id => $name)

                                <option
                                    @if (isset($user) && in_array($id, $user->roles()->pluck('id')->toArray()))
                                        selected
                                    @elseif(old('role_id') && in_array($id, old('role_id')))
                                        selected
                                    @endif
                                    value="{{ $name }}">

                                    {{ $name }}

                                </option>

                            @endforeach

                        </select>

                        @error('role_id')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>

                {{-- الأزرار --}}
                <div class="text-center mt-5">

                    <button type="submit" class="btn-save">

                        <i class="bx bx-save"></i>

                        {{ isset($user) ? 'حفظ التعديلات' : 'إضافة المستخدم' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('footerScripts')
    <script></script>
@endpush