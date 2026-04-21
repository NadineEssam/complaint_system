@extends('dashboard.layouts.app')
@push('headScripts')
    <style>
        .page-breadcrumb .breadcrumb {
            background-color: transparent;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            display: inline-block;
            padding-right: 0.5rem;
            padding-left: 0.5rem;
            color: #6c757d;
            content: "/";
        }
    </style>
@endpush
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
                <div class="pr-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 shadow-none">
                            <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page">
                                {{ isset($role) ? 'تعديل الدور: ' . $role->name : 'إضافة دور جديد' }}</li>

                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-secondary"><i
                                        class="bx bx-shape-polygon"></i> الأدوار</a></li>

                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-secondary"><i
                                        class="bx bx-home-alt"></i> الرئيسية</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card radius-15 shadow-sm border-0" dir="rtl" style="text-align: right;">
                <div class="card-body">
                    <div class="card-header bg-white mb-4 d-flex align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold"><i class="bx bx-shield-quarter ml-2"></i>
                            {{ isset($role) ? 'تعديل بيانات الدور' : 'بيانات الدور الجديد' }}</h5>
                    </div>

                    <!-- General Form Elements -->
                    <form method="POST"
                        action="{{ isset($role) ? route('roles.update', ['role' => $role]) : route('roles.store') }}">
                        @if (isset($role))
                            @method('PUT')
                        @endif
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label font-weight-bold">اسم الدور</label>
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
                            <label class="col-sm-2 col-form-label font-weight-bold">الصلاحيات</label>
                            <div class="col-sm-10">
                                @include('dashboard.include.permissions_table')
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i
                                        class="bx bx-save ml-1"></i> حفظ البيانات</button>
                            </div>
                        </div>
                    </form>
                    <!-- End General Form Elements -->

                </div>
            </div>
        </div>
    </div>
@endsection

@push('footerScripts')
    <script src="{{ asset('js/permissions_table.js') }}"></script>
@endpush
