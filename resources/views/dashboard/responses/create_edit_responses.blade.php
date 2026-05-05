@extends('dashboard.layouts.app')

@section('title', 'إضافة / تعديل رد')

@push('headScripts')
<style>
.stepwizard { display: table; width: 100%; position: relative; }
.stepwizard-row { display: table-row; }
.stepwizard-step { display: table-cell; text-align: center; }
.stepwizard-row:before {
    top: 14px;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
}
.btn-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    padding: 6px;
}
.setup-content { display: none; }
</style>
@endpush

@section('content')

<div class="page-content-wrapper">
<div class="page-content">

<div class="card">
<div class="card-body">

<h5 class="mb-4">
    {{ isset($response) ? 'تعديل رد الشكوى' : 'إضافة رد للشكوى' }}
    #{{ $complaint->ComplaintID }}
</h5>

{{-- STEP HEADER --}}
<div class="stepwizard mb-4">
    <div class="stepwizard-row setup-panel">

        <div class="stepwizard-step">
            <a href="#step-1" class="btn btn-primary btn-circle">1</a>
            <p>البيانات الأساسية</p>
        </div>

        <div class="stepwizard-step">
            <a href="#step-2" class="btn btn-default btn-circle">2</a>
            <p>تفاصيل إضافية</p>
        </div>

        <div class="stepwizard-step">
            <a href="#step-3" class="btn btn-default btn-circle">3</a>
            <p>الحفظ</p>
        </div>

    </div>
</div>

<form method="POST"
      action="{{ isset($response) 
        ? route('responses.update', $response->id) 
        : route('responses.store') }}">

    @csrf

    @if(isset($response))
        @method('PUT')
    @endif

    <input type="hidden" name="complaint_id" value="{{ $complaint->ComplaintID }}">

    {{-- STEP 1 --}}
    <div class="setup-content" id="step-1">

        <div class="mb-3">
            <label>حالة الطلب</label>
            <select class="form-select" name="ComplaintStatus" required>
                <option value="">اختر</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->statusID }}"
                        {{ old('ComplaintStatus', $response->ComplaintStatus ?? '') == $status->statusID ? 'selected' : '' }}>
                        {{ $status->statusText }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>تفاصيل الحالة</label>
            <textarea class="form-control" name="ComplaintText">{{ old('ComplaintText', $response->ComplaintText ?? '') }}</textarea>
        </div>

        <button type="button" class="btn btn-primary nextBtn">التالي</button>

    </div>

    {{-- STEP 2 --}}
    <div class="setup-content" id="step-2">

        <div class="mb-3">
            <label>نوع الخدمة</label>
            <select class="form-select" name="ComplaintService">
                <option value="">اختر</option>
                @foreach ($serviceTypes as $service)
                    <option value="{{ $service->srevicetyptid }}"
                        {{ old('ComplaintService', $response->ComplaintService ?? '') == $service->srevicetyptid ? 'selected' : '' }}>
                        {{ $service->srevicetyptname }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>سبب الشكوى</label>
            <select class="form-select" name="fk_close_reason_id">
                <option value="">اختر</option>
                @foreach ($closeReasons as $reason)
                    <option value="{{ $reason->close_reason_ID }}"
                        {{ old('fk_close_reason_id', $response->fk_close_reason_id ?? '') == $reason->close_reason_ID ? 'selected' : '' }}>
                        {{ $reason->close_reason_Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>التصنيف</label>
            <select class="form-select" name="fk_close_reason_classify_id">
                <option value="">اختر</option>
                @foreach ($classifications as $classify)
                    <option value="{{ $classify->close_reason_classify_id }}"
                        {{ old('fk_close_reason_classify_id', $response->fk_close_reason_classify_id ?? '') == $classify->close_reason_classify_id ? 'selected' : '' }}>
                        {{ $classify->close_reason_classify_Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="button" class="btn btn-primary nextBtn">التالي</button>

    </div>

    {{-- STEP 3 --}}
    <div class="setup-content" id="step-3">

        <h5>تأكيد وحفظ</h5>

        <button type="submit" class="btn btn-success">
            {{ isset($response) ? 'تحديث الرد' : 'حفظ الرد' }}
        </button>

    </div>

</form>

</div>
</div>

</div>
</div>

@endsection

@push('footerScripts')
<script>
$(document).ready(function () {

    var allWells = $('.setup-content');

    allWells.hide();
    $('#step-1').show();

    $('.nextBtn').click(function () {
        var curStep = $(this).closest(".setup-content");
        var nextStep = curStep.next(".setup-content");

        curStep.hide();
        nextStep.show();
    });

});
 function toggleFields() {
        let status = $('#statusSelect').val();

        if (status == 2 || status == 4) {
            $('#reasonSelect').prop('disabled', false);
            $('#classifySelect').prop('disabled', false);
        } else {
            $('#reasonSelect').prop('disabled', true);
            $('#classifySelect').prop('disabled', true);
        }
    }

    toggleFields();

    $('#statusSelect').on('change', function () {
        toggleFields();
    });

</script>
@endpush