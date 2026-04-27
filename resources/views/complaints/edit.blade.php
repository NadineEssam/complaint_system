@extends('layouts.app')

@section('title', 'تعديل الشكوى')

@section('content')

<div class="pagetitle">
    <h1>تعديل الشكوى</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            <form action="{{ route('complaints.update', $complaint->ComplaintID) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Tabs -->
                <ul class="nav nav-tabs d-flex" id="complaintTabs" role="tablist">

                    <li class="nav-item flex-fill">
                        <button class="nav-link w-100 active" data-bs-toggle="tab" data-bs-target="#personal" type="button">
                            البيانات الشخصية
                        </button>
                    </li>

                    <li class="nav-item flex-fill">
                        <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#complaint" type="button">
                            تفاصيل الطلب
                        </button>
                    </li>

                </ul>

                <div class="tab-content pt-3">

                    <!-- ================= TAB 1 ================= -->
                    <div class="tab-pane fade show active" id="personal">

                        <!-- Request Type -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">نوع الطلب</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="requesttypeid">
                                    <option value="">اختر</option>
                                    @foreach ($requestTypes as $type)
                                        <option value="{{ $type->requesttypeid }}"
                                            {{ $complaint->RequestType == $type->requesttypeid ? 'selected' : '' }}>
                                            {{ $type->requesttypename }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">اسم العميل</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                    name="ComplainerName"
                                    value="{{ $complaint->ComplainerName }}">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">البريد</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control"
                                    name="ComplainerEmail"
                                    value="{{ $complaint->ComplainerEmail }}">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">الهاتف</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                    name="ComplainerPhone"
                                    value="{{ $complaint->ComplainerPhone }}">
                            </div>
                        </div>

                        <!-- Governorate -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">المحافظة</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="ComplaintGovernorate">
                                    @foreach ($govs as $gov)
                                        <option value="{{ $gov->govsid }}"
                                            {{ $complaint->ComplaintGovernorate == $gov->govsid ? 'selected' : '' }}>
                                            {{ $gov->govname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- ================= TAB 2 ================= -->
                    <div class="tab-pane fade" id="complaint">

                        <!-- Date -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">التاريخ</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control"
                                    name="ComplaintDate"
                                    value="{{ $complaint->ComplaintDate }}">
                            </div>
                        </div>

                        <!-- Sector -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">القطاع</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="sector_id">
                                    @foreach ($sectors as $sector)
                                        <option value="{{ $sector->sector_id }}"
                                            {{ $complaint->department == $sector->sector_id ? 'selected' : '' }}>
                                            {{ $sector->sector_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Sources -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">مصدر الشكوى</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="comsources[]" multiple>
                                    @foreach ($comsources as $source)
                                        <option value="{{ $source->comsourcesid }}"
                                            {{ in_array($source->comsourcesid, $complaint->sources->pluck('comsourcesid')->toArray()) ? 'selected' : '' }}>
                                            {{ $source->comsourcesname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Office -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">المكتب</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="office">
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->ID }}"
                                            {{ $complaint->office == $office->ID ? 'selected' : '' }}>
                                            {{ $office->REG_OFFIC_NAMA }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Text -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">نص الشكوى</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="ComplaintText">{{ $complaint->ComplaintText }}</textarea>
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="d-grid mt-3">
                            <button class="btn btn-primary">
                                حفظ التعديل
                            </button>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>
</section>

@endsection