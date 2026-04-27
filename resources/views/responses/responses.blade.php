@extends('layouts.app')

@section('title', 'الرد على الشكوى')

@section('content')

<div class="pagetitle">
    <h1>الرد على الشكوى #{{ $complaint->ComplaintID }}</h1>
</div>

<section class="section">

    <!-- Existing responses -->
    <div class="card mb-3">
        <div class="card mb-3">
            <div class="card-body">
                <h5>سجل الردود</h5>

                <table id="responsesTable" data-id="{{ $complaint->ComplaintID }}" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الحالة</th>
                            <th>تفاصيل</th>
                            <th>الخدمة</th>
                            <th>تاريخ الرد</th>
                            <th>عرض</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

   

</section>

@endsection
<div class="modal fade" id="responseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الرد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Wizard Steps -->
                <ul class="nav nav-pills mb-3" id="wizardSteps">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#step1">1. البيانات</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#step2">2. الخدمة</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#step3">3. الأسباب</a></li>
                </ul>

                <div class="tab-content">

                    <!-- Step 1 -->
                    <div class="tab-pane fade show active" id="step1">
                        <p><strong>رقم الشكوى:</strong> <span id="modalComplaintId"></span></p>
                        <p><strong>الحالة:</strong> <span id="modalStatus"></span></p>
                        <p><strong>تفاصيل:</strong> <span id="modalText"></span></p>
                    </div>

                    <!-- Step 2 -->
                    <div class="tab-pane fade" id="step2">
                        <p><strong>نوع الخدمة:</strong> <span id="modalService"></span></p>
                    </div>

                    <!-- Step 3 -->
                    <div class="tab-pane fade" id="step3">
                        <p><strong>سبب الشكوى:</strong> <span id="modalReason"></span></p>
                        <p><strong>التصنيف:</strong> <span id="modalClassify"></span></p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('assets/js/responses.js') }}"></script>
@endpush