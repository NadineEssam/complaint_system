@extends('layouts.app')

@section('title', 'تفاصيل الرد')

@section('content')

<div class="pagetitle">
    <h1>تفاصيل الرد</h1>
</div>

<section class="section">

    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                <h5 class="mb-0">
                    الرد الخاص بالشكوى #{{ $response->complaint_id }}
                </h5>

                <a href="{{ route('complaints.responses', $response->complaint_id) }}"
                   class="btn btn-secondary">
                    رجوع
                </a>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">رقم الشكوى</label>
                <div class="col-sm-9">
                    {{ $response->complaint_id }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 fw-bold">الحالة</label>
                <div class="col-sm-9">
                    {{ $response->status->statusText ?? '-' }}
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
                    {{ $response->serviceType->srevicetyptname ?? '-' }}
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

            <div class="row mb-4">
                <label class="col-sm-3 fw-bold">تاريخ الإنشاء</label>
                <div class="col-sm-9">
                    {{ $response->created_at ? $response->created_at->format('Y-m-d H:i') : '-' }}
                </div>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ url('/responses/'.$response->id.'/edit') }}"
                   class="btn btn-warning">
                    تعديل
                </a>

                <a href="{{ route('complaints.responses', $response->complaint_id) }}"
                   class="btn btn-primary">
                    العودة للسجل
                </a>

            </div>

        </div>
    </div>

</section>

@endsection