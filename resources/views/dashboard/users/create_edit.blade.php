@extends('dashboard.layouts.app')

@push('headScripts')
@endpush

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">

                <div class="pl-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 shadow-none">

                            <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page">
                                {{ isset($user) ? 'تعديل المستخدم: ' . $user->userID : 'إضافة مستخدم جديد' }}
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('users.index') }}" class="text-secondary">
                                    <i class="bx bx-user"></i> المستخدمون
                                </a>
                            </li>
                            
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-secondary">
                                    <i class="bx bx-home-alt"></i> الرئيسية
                                </a>
                            </li>

                            

                            

                        </ol>
                    </nav>
                </div>

            </div>
            <!-- end breadcrumb -->


            <div class="card radius-15 border-lg-top-primary">
                <div class="card-body">

                    <div class="card-header bg-white mb-4 d-flex align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="bx bx-user-circle ml-2"></i>

                            {{ isset($user) ? 'تعديل بيانات المستخدم' : 'بيانات المستخدم الجديد' }}
                        </h5>
                    </div>

                    <form method="POST"
                        action="{{ isset($user) ? route('users.update', ['user' => $user]) : route('users.store') }}"
                        enctype="multipart/form-data">

                        @if (isset($user))
                            @method('PUT')
                        @endif

                        @csrf


                        {{-- اسم المستخدم --}}
                        <div class="row mb-4">
                            <label for="userid" class="col-sm-2 col-form-label font-weight-bold">
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
                            <label for="role_id" class="col-sm-2 col-form-label font-weight-bold">
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

                            <button type="submit" class="btn btn-primary px-5">

                                <i class="bx bx-save mr-1"></i>

                                {{ isset($user) ? 'حفظ التعديلات' : 'إضافة المستخدم' }}

                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection


@push('footerScripts')
    <script></script>
@endpush