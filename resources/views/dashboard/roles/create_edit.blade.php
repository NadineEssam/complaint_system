@extends('dashboard.layouts.app')
@push('headScripts')

@endpush
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-md-flex align-items-center mb-3">

                <div class="breadcrumb-title pr-3">{{ (isset($role))?__('Edit :type',['type'=>$role->name]):__('Create') }}</div>
                <div class="pl-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i> {{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}"><i class="bx bx-shape-polygon"></i> {{ __('Roles') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ (isset($role))?__('Edit :type',['type'=>$role->name]):__('Create') }}</li>
                        </ol>
                    </nav>
                </div>
                {{--                <div class="ml-auto">--}}
                {{--                    <div class="btn-group">--}}
                {{--                        <button type="button" class="btn btn-primary">Settings</button>--}}
                {{--                        <button type="button" class="btn btn-primary bg-split-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">	<span class="sr-only">Toggle Dropdown</span>--}}
                {{--                        </button>--}}
                {{--                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">	<a class="dropdown-item" href="javascript:;">Action</a>--}}
                {{--                            <a class="dropdown-item" href="javascript:;">Another action</a>--}}
                {{--                            <a class="dropdown-item" href="javascript:;">Something else here</a>--}}
                {{--                            <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
            <!--end breadcrumb-->
            <div class="card radius-15 border-lg-top-primary">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">{{ (isset($role))?__('Edit :type',['type'=>$role->name]):__('Create') }}</h4>
                    </div>
                    <hr>
                    <form method="POST" action="{{ isset($role)?route('roles.update',['role'=>$role]):route('roles.store') }}">
                        @if(isset($role))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-shape-polygon"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror border-left-0" name="name" id="name" value="{{ isset($role)?$role->name:old('name') }}" placeholder="{{ __('Enter :value',['value'=>__('Role Name')]) }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>

                        </div>
                        @include('dashboard.include.permissions_table')
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger px-5">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footerScripts')
    <script src="{{ asset('js/permissions_table.js') }}"></script>
@endpush
