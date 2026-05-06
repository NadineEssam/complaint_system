@extends('dashboard.layouts.app')

@push('headScripts')
<style>
    .page-breadcrumb .breadcrumb {
        background-color: transparent;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        padding: 0 0.5rem;
        color: #6c757d;
        content: "/";
    }

    body {
        margin-top: 40px;
    }

    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }

    .stepwizard-row {
        display: table-row;
    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .stepwizard-step p {
        margin-top: 10px;
    }

    .stepwizard-row:before {
        top: 14px;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-index: 0;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        border-radius: 15px;
        font-size: 12px;
    }

    .is-invalid {
        border: 1px solid red !important;
    }

    .error-text {
        color: red;
        font-size: 13px;
        margin-top: 5px;
    }

    .setup-content {
        display: none;
    }

    .setup-content.active {
        display: block;
    }
</style>
@endpush

@section('content')

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
            <div class="pr-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 shadow-none">
                        <li class="breadcrumb-item active text-primary font-weight-bold">
                            {{ isset($complaint) ? 'تعديل الشكوى' : 'إضافة شكوى' }}
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('complaints.index') }}">الشكاوى</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="card radius-15 shadow-sm border-0" dir="rtl" style="text-align: right;">
                    <div class="card-body">

                        <div class="card-header bg-white mb-4 d-flex align-items-center">
                            <h5 class="mb-0 text-primary font-weight-bold">
                                <i class="bx bx-shield-quarter ml-2"></i>
                                {{ isset($complaint) ? 'تعديل الشكوى' : 'إضافة شكوى' }}
                            </h5>
                        </div>

                        {{-- STEP WIZARD --}}
                        <div class="stepwizard mb-4">
                            <div class="stepwizard-row setup-panel">

                                <div class="stepwizard-step">
                                    <a href="#step-1" class="btn btn-primary btn-circle step-link">1</a>
                                    <p>البيانات الشخصية</p>
                                </div>

                                <div class="stepwizard-step">
                                    <a href="#step-2" class="btn btn-default btn-circle step-link">2</a>
                                    <p>تفاصيل الشكوى</p>
                                </div>

                            </div>
                        </div>

                        <form method="POST"
                            action="{{ isset($complaint) ? route('complaints.update', $complaint) : route('complaints.store') }}">

                            @csrf
                            @if(isset($complaint))
                            @method('PUT')
                            @endif

                            {{-- ================= STEP 1 ================= --}}
                            <div class="row setup-content" id="step-1">
                                <div class="col-md-12">

                                    <h4>البيانات الشخصية</h4>

                                    {{-- Request Type --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">نوع الطلب</label>
                                        <div class="col-sm-10">
                                            <select class="form-select @error('requesttypeid') is-invalid @enderror"
                                                name="requesttypeid" id="requesttypeid">
                                                <option value="">اختر</option>
                                                @foreach($requestTypes as $type)
                                                <option value="{{ $type->requesttypeid }}"
                                                    {{ old('requesttypeid', $complaint->RequestType ?? '') == $type->requesttypeid ? 'selected' : '' }}>
                                                    {{ $type->requesttypename }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('requesttypeid')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="nationalIdSection">
                                        <div class="row mb-3"> <label class="col-sm-2 col-form-label">الرقم القومي</label>
                                            <div class="col-sm-10"> <input type="text" class="form-control" name="ComplaintNationalID" autocomplete="off">
                                                <div class="error-text" id="nidError"></div>
                                            </div>
                                        </div>
                                        <div class="row mb-3"> <label class="col-sm-2 col-form-label">النوع</label>
                                            <div class="col-sm-10"> <input type="text" class="form-control" name="ComplainerGender" readonly> </div>
                                        </div>
                                    </div>

                                    {{-- Name --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">اسم العميل</label>
                                        <div class="col-sm-10">
                                            <input type="text"
                                                class="form-control @error('ComplainerName') is-invalid @enderror"
                                                name="ComplainerName">
                                            @error('ComplainerName')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">البريد</label>
                                        <div class="col-sm-10">
                                            <input type="email"
                                                class="form-control @error('ComplainerEmail') is-invalid @enderror"
                                                name="ComplainerEmail">
                                            @error('ComplainerEmail')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Phone --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">الهاتف</label>
                                        <div class="col-sm-10">
                                            <input type="text"
                                                class="form-control @error('ComplainerPhone') is-invalid @enderror"
                                                name="ComplainerPhone">
                                            @error('ComplainerPhone')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary nextBtn">التالي</button>

                                </div>
                            </div>

                            {{-- ================= STEP 2 ================= --}}
                            <div class="row setup-content" id="step-2">
                                <div class="col-md-12">

                                    <h4>تفاصيل الشكوى</h4>

                                    {{-- Date --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">التاريخ</label>
                                        <div class="col-sm-10">
                                            <input type="date"
                                                class="form-control @error('ComplaintDate') is-invalid @enderror"
                                                name="ComplaintDate">
                                            @error('ComplaintDate')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Sector --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">القطاع</label>
                                        <div class="col-sm-10">
                                            <select class="form-select @error('sector_id') is-invalid @enderror"
                                                name="sector_id">
                                                <option value="">اختر</option>
                                                @foreach($sectors as $sector)
                                                <option value="{{ $sector->sector_id }}">
                                                    {{ $sector->sector_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('sector_id')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Source --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">مصدر الشكوى</label>
                                        <div class="col-sm-10">
                                            <select class="form-select @error('comsource_id') is-invalid @enderror"
                                                name="comsource_id">
                                                <option value="">اختر</option>
                                                @foreach($comsources as $source)
                                                <option value="{{ $source->comsourcesid }}">
                                                    {{ $source->comsourcesname }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('comsource_id')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Governorate --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">المحافظة</label>
                                        <div class="col-sm-10">
                                            <select class="form-select @error('ComplaintGovernorate') is-invalid @enderror"
                                                id="governorateSelect"
                                                name="ComplaintGovernorate">
                                                <option value="">اختر المحافظة</option>
                                                @foreach ($govs as $gov)
                                                <option value="{{ $gov->govsid }}">
                                                    {{ $gov->govname }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('ComplaintGovernorate')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Office --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">المكتب</label>
                                        <div class="col-sm-10">
                                            <select class="form-select @error('office') is-invalid @enderror"
                                                id="officeSelect"
                                                name="office">
                                                <option value="">اختر المكتب</option>
                                                @foreach ($offices as $office)
                                                <option value="{{ $office->ID }}"
                                                    data-gov="{{ $office->FK_GOVT_CODE }}">
                                                    {{ $office->REG_OFFIC_NAMA }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('office')
                                            <div class="error-text">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">

                                        <button type="button" class="btn btn-secondary prevBtn">
                                            رجوع
                                        </button>

                                        <button type="submit" class="btn btn-success">
                                            حفظ الشكوى
                                        </button>

                                    </div>

                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

        @endsection

        @push('footerScripts')
        <script src="{{ asset('assets/js/complaints.js') }}"></script>
        @endpush