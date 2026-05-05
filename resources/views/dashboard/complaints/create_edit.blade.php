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

    .setup-content {
        display: none;
    }

    .is-invalid {
        border: 1px solid red !important;
    }

    .error-text {
        color: red;
        font-size: 13px;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">
                    {{ isset($complaint) ? 'تعديل الشكوى' : 'إضافة شكوى' }}
                </li>
            </ol>
        </div>

        <div class="card">
            <div class="card-body">

                {{-- STEP WIZARD HEADER --}}
                <div class="stepwizard mb-4">
                    <div class="stepwizard-row setup-panel">

                        <div class="stepwizard-step">
                            <a href="#step-1" class="btn btn-primary btn-circle">1</a>
                            <p>البيانات الشخصية</p>
                        </div>

                        <div class="stepwizard-step">
                            <a href="#step-2" class="btn btn-default btn-circle" disabled>2</a>
                            <p>تفاصيل الشكوى</p>
                        </div>

                        <div class="stepwizard-step">
                            <a href="#step-3" class="btn btn-default btn-circle" disabled>3</a>
                            <p>الحفظ</p>
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

                                    <select class="form-select" name="requesttypeid" id="requesttypeid" required>
                                        <option value="">اختر</option>
                                        @foreach($requestTypes as $type)
                                        <option value="{{ $type->requesttypeid }}"
                                            {{ old('requesttypeid', $complaint->RequestType ?? '') == $type->requesttypeid ? 'selected' : '' }}>
                                            {{ $type->requesttypename }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('requesttypeid')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>

                            {{-- National ID --}}
                            <div id="nationalIdSection">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">الرقم القومي</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                            name="ComplaintNationalID"
                                            value="{{ old('ComplaintNationalID', $complaint->ComplaintNationalID ?? '') }}">

                                        @error('ComplaintNationalID')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">النوع</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                            name="ComplainerGender"
                                            value="{{ old('ComplainerGender', $complaint->ComplainerGender ?? '') }}" readonly>

                                        @error('ComplainerGender')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">اسم العميل</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        name="ComplainerName"
                                        value="{{ old('ComplainerName', $complaint->ComplainerName ?? '') }}">

                                    @error('ComplainerName')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">البريد</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control"
                                        name="ComplainerEmail"
                                        value="{{ old('ComplainerEmail', $complaint->ComplainerEmail ?? '') }}">

                                    @error('ComplainerEmail')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">الهاتف</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        name="ComplainerPhone"
                                        value="{{ old('ComplainerPhone', $complaint->ComplainerPhone ?? '') }}">

                                    @error('ComplainerPhone')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Governorate --}}
                            <!-- <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">المحافظة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="ComplaintGovernorate">
                                        <option value="">اختر</option>
                                        @foreach($govs as $gov)
                                        <option value="{{ $gov->govsid }}"
                                            {{ isset($complaint) && $complaint->ComplaintGovernorate == $gov->govsid ? 'selected' : '' }}>
                                            {{ $gov->govname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->

                            <button type="button" class="btn btn-primary nextBtn">Next</button>

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
                                    <input type="date" class="form-control"
                                        name="ComplaintDate"
                                        value="{{ old('ComplaintDate', $complaint->ComplaintDate ?? '') }}">

                                    @error('ComplaintDate')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Sector --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">القطاع</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="sector_id">

                                        <option value="">اختر</option>

                                        @foreach($sectors as $sector)
                                        <option value="{{ $sector->sector_id }}"
                                            {{ old('sector_id', $complaint->department ?? '') == $sector->sector_id ? 'selected' : '' }}>
                                            {{ $sector->sector_name }}
                                        </option>
                                        @endforeach

                                    </select>

                                    @error('sector_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>

                            {{-- Sources --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">مصادر الشكوى</label>

                                <div class="col-sm-10">
                                    <select class="form-select" name="comsource_id" required>

                                        <option value="">اختر مصدر الشكوى</option>

                                        @foreach($comsources as $source)
                                        <option value="{{ $source->comsourcesid }}"
                                            {{ old('comsource_id', $complaint->comsource_id ?? '') == $source->comsourcesid ? 'selected' : '' }}>
                                            {{ $source->comsourcesname }}
                                        </option>
                                        @endforeach

                                    </select>

                                    @error('comsource_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">المحافظة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="governorateSelect" name="ComplaintGovernorate">

                                        <option value="">اختر المحافظة</option>

                                        @foreach ($govs as $gov)
                                        <option value="{{ $gov->govsid }}"
                                            {{ old('ComplaintGovernorate', $complaint->ComplaintGovernorate ?? '') == $gov->govsid ? 'selected' : '' }}>
                                            {{ $gov->govname }}
                                        </option>
                                        @endforeach

                                    </select>

                                    @error('ComplaintGovernorate')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Office --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">المكتب</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="officeSelect" name="office">

                                        <option value="">اختر المكتب</option>

                                        @foreach ($offices as $office)
                                        <option value="{{ $office->ID }}"
                                            data-gov="{{ $office->FK_GOVT_CODE }}"
                                            {{ old('office', $complaint->office ?? '') == $office->ID ? 'selected' : '' }}>
                                            {{ $office->REG_OFFIC_NAMA }}
                                        </option>
                                        @endforeach

                                    </select>

                                    @error('office')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary nextBtn">Next</button>

                        </div>
                    </div>

                    {{-- ================= STEP 3 ================= --}}
                    <div class="row setup-content" id="step-3">
                        <div class="col-md-12">

                            <h4>الحفظ</h4>

                            <button type="submit" class="btn btn-success">
                                حفظ الشكوى
                            </button>

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