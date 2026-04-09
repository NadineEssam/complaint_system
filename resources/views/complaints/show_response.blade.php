@extends('layouts.app')

@section('title', 'تفاصيل الرد')

@section('content')

<div class="pagetitle">
    <h1>تفاصيل الرد</h1>
</div>

<section class="section">

    <div class="card">
        <div class="card-body pt-4">

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">رقم الشكوى</label>
                <div class="col-sm-9">
                    {{ $response->complaint_id }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">الحالة</label>
                <div class="col-sm-9">
                    @switch($response->ComplaintStatus)
                        @case(1) Pending @break
                        @case(2) In Progress @break
                        @case(3) Closed @break
                        @default Unknown
                    @endswitch
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">تفاصيل الحالة</label>
                <div class="col-sm-9">
                    {{ $response->ComplaintText ?? '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">نوع الخدمة</label>
                <div class="col-sm-9">
                    {{ $response->ComplaintServiceName ?? '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">سبب الشكوى</label>
                <div class="col-sm-9">
                    {{ $response->closeReason->close_reason_Name ?? '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">تفاصيل السبب</label>
                <div class="col-sm-9">
                    {{ $response->classification->close_reason_classify_Name ?? '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">تاريخ الرد</label>
                <div class="col-sm-9">
                    {{ $response->created_at ? $response->created_at->format('Y-m-d H:i') : '-' }}
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    رجوع
                </a>
            </div>

        </div>
    </div>

</section>

@endsection