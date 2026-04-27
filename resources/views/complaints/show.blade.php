@extends('layouts.app')

@section('title', 'عرض الشكوى')

@section('content')

<div class="pagetitle">
    <h1>عرض الشكوى</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            <div class="row mt-3">
                <div class="col-md-6">
                    <label>اسم العميل:</label>
                    <p>{{ $complaint->ComplainerName }}</p>
                </div>

                <div class="col-md-6">
                    <label>البريد:</label>
                    <p>{{ $complaint->ComplainerEmail }}</p>
                </div>

                <div class="col-md-6">
                    <label>الهاتف:</label>
                    <p>{{ $complaint->ComplainerPhone }}</p>
                </div>

                <div class="col-md-6">
                    <label>الحالة:</label>
                    <p>{{ $complaint->status->statusText ?? '-' }}</p>
                </div>

                <div class="col-12">
                    <label>نص الشكوى:</label>
                    <p>{{ $complaint->ComplaintText }}</p>
                </div>
            </div>

            <a href="{{ route('complaints.index') }}" class="btn btn-secondary mt-3">
                رجوع
            </a>

        </div>
    </div>
</section>

@endsection