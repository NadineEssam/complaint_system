@extends('dashboard.layouts.app')
@push('headScripts')
@endpush
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">

              
                <div class="pl-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 shadow-none">
                            <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page">
                                {{ isset($user) ? 'تعديل المستخدم: ' . $user->userID : 'إضافة مستخدم جديد' }}</li>

                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-secondary"><i
                                        class="bx bx-shape-polygon"></i> المستخدمون</a></li>

                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-secondary"><i
                                        class="bx bx-home-alt"></i> الرئيسية</a></li>
                        </ol>
                        
                    </nav>
                </div>
                {{--                <div class="ml-auto"> --}}
                {{--                    <div class="btn-group"> --}}
                {{--                        <button type="button" class="btn btn-primary">Settings</button> --}}
                {{--                        <button type="button" class="btn btn-primary bg-split-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">	<span class="sr-only">Toggle Dropdown</span> --}}
                {{--                        </button> --}}
                {{--                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">	<a class="dropdown-item" href="javascript:;">Action</a> --}}
                {{--                            <a class="dropdown-item" href="javascript:;">Another action</a> --}}
                {{--                            <a class="dropdown-item" href="javascript:;">Something else here</a> --}}
                {{--                            <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a> --}}
                {{--                        </div> --}}
                {{--                    </div> --}}
                {{--                </div> --}}
            </div>
            <!--end breadcrumb-->
            <div class="card radius-15 border-lg-top-primary">
                <div class="card-body">
                     <div class="card-header bg-white mb-4 d-flex align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold"><i class="bx bx-shield-quarter ml-2"></i>
                            {{ isset($role) ? 'تعديل بيانات المستخدم' : 'بيانات المستخدم الجديد' }}</h5>
                    </div>

                    <form method="POST"
                        action="{{ isset($user) ? route('users.update', ['user' => $user]) : route('users.store') }}"
                        enctype="multipart/form-data">
                        @if (isset($user))
                            @method('PUT')
                        @endif
                        @csrf



            
                        <div class="row mb-3">
                            <label for="userid" class="col-sm-2 col-form-label font-weight-bold">اسم الدور</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('userid') is-invalid @enderror"
                                  {{ isset($user) ? 'readonly': '' }}
                                    name="userid" id="userid" value="{{ isset($user) ? $user->userID : old('userid') }}"
                                    placeholder="أدخل اسم المستخدم">
                                @error('userid')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label font-weight-bold"> اختر الدور و الصلاحيات
                            </label>
                            <div class="col-sm-10">
                                <select id="role_id"  class=" form-select  form-select-lg  px-5" required
                                    data-placeholder="{{ __('Select :type', ['type' => __('Role')]) }}" name="role_id[]">
                                    <option  value=""> اختر الدور و الصلاحيات </option>
                                    @foreach (\Spatie\Permission\Models\Role::where('guard_name', 'web')->pluck('name', 'id')->toArray() as $id => $name)
                                        <option 
                                            @if (isset($user) && in_array($id, $user->roles()->pluck('id')->toArray())) selected="selected" @elseif(old('role_id') && in_array($id, old('role_id'))) selected="selected" @endif
                                            value="{{$name}}">{{ $name }}</option>
                                    @endforeach
                                </select>


                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        {{-- <div class="form-group col-lg-12">
                            <label>{{ __('Roles') }}</label>

                            <select id="role_id" class="select2" multiple
                                data-placeholder="{{ __('Select :type', ['type' => __('Role')]) }}" name="role_id[]">
                                @foreach (\Spatie\Permission\Models\Role::where('guard_name', 'web')->pluck('name', 'id')->toArray() as $id => $name)
                                    <option
                                        @if (isset($user) && in_array($id, $user->roles()->pluck('id')->toArray())) selected="selected" @elseif(old('role_id') && in_array($id, old('role_id'))) selected="selected" @endif
                                        value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <!-- </div> -->
                        </div> --}}




                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('footerScripts')
    <script src="{{ asset('js/permissions_table.js') }}"></script>


    <script></script>
@endpush
