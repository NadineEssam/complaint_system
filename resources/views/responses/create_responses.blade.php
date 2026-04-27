@extends('layouts.app')

@section('title', 'إضافة رد جديد')

@section('content')

<div class="pagetitle">
    <h1>إضافة رد جديد للشكوى #{{ $complaint->ComplaintID }}</h1>
</div>

<section class="section">

    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                <h5 class="mb-0">نموذج إضافة رد</h5>

                <a href="{{ route('complaints.responses', $complaint->ComplaintID) }}"
                   class="btn btn-secondary">
                    رجوع
                </a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('complaints.responses.store') }}">
                @csrf

                <input type="hidden"
                       name="complaint_id"
                       value="{{ $complaint->ComplaintID }}">

                <!-- حالة الطلب -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">
                        حالة الطلب
                    </label>

                    <div class="col-sm-10">
                        <select class="form-select"
                                name="ComplaintStatus"
                                id="statusSelect"
                                required>

                            <option value="">اختر</option>

                            @foreach ($statuses as $status)
                                <option value="{{ $status->statusID }}"
                                    {{ old('ComplaintStatus') == $status->statusID ? 'selected' : '' }}>
                                    {{ $status->statusText }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <!-- تفاصيل الحالة -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">
                        تفاصيل الحالة
                    </label>

                    <div class="col-sm-10">
                        <textarea class="form-control"
                                  name="ComplaintText"
                                  rows="4">{{ old('ComplaintText') }}</textarea>
                    </div>
                </div>

                <!-- نوع الخدمة -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">
                        نوع الخدمة
                    </label>

                    <div class="col-sm-10">
                        <select class="form-select"
                                name="ComplaintService">

                            <option value="">اختر</option>

                            @foreach ($serviceTypes as $service)
                                <option value="{{ $service->srevicetyptid }}"
                                    {{ old('ComplaintService') == $service->srevicetyptid ? 'selected' : '' }}>
                                    {{ $service->srevicetyptname }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <!-- سبب الشكوى -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">
                        سبب الشكوى
                    </label>

                    <div class="col-sm-10">
                        <select class="form-select"
                                name="fk_close_reason_id"
                                id="reasonSelect">

                            <option value="">اختر</option>

                            @foreach ($closeReasons as $reason)
                                <option value="{{ $reason->close_reason_ID }}"
                                    {{ old('fk_close_reason_id') == $reason->close_reason_ID ? 'selected' : '' }}>
                                    {{ $reason->close_reason_Name }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <!-- تصنيف السبب -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">
                        تفاصيل سبب الشكوى
                    </label>

                    <div class="col-sm-10">
                        <select class="form-select"
                                name="fk_close_reason_classify_id"
                                id="classifySelect">

                            <option value="">اختر</option>

                            @foreach ($classifications as $classify)
                                <option value="{{ $classify->close_reason_classify_id }}"
                                    {{ old('fk_close_reason_classify_id') == $classify->close_reason_classify_id ? 'selected' : '' }}>
                                    {{ $classify->close_reason_classify_Name }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <!-- Save -->
                <div class="d-grid gap-2 mt-4">
                    <button type="submit"
                            class="btn btn-primary">
                        حفظ الرد
                    </button>
                </div>

            </form>

        </div>
    </div>

</section>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/responses.js') }}"></script>
@endpush