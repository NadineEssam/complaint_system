@extends('layouts.app')

@section('title', 'بيانات الشكوى')

@section('content')

    <div class="pagetitle">
        <h1>بيانات الشكوى</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                <!-- Tabs -->
                <form action="{{ route('complaints.store') }}" method="POST">
                    @csrf

                    <!-- Tabs -->
                    <ul class="nav nav-tabs d-flex" id="complaintTabs" role="tablist">

                        <!-- Tab 1 -->
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100 active" id="personal-tab" data-bs-toggle="tab"
                                data-bs-target="#personal" type="button">
                                البيانات الشخصية لمقدم الطلب
                            </button>
                        </li>

                        <!-- Tab 2 -->
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="complaint-tab" data-bs-toggle="tab"
                                data-bs-target="#complaint" type="button">
                                تفاصيل بيانات الطلب
                            </button>
                        </li>

                        <!-- Tab 3 -->
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="response-tab" data-bs-toggle="tab"
                                data-bs-target="#complaintResponse" type="button">
                                الرد على الطلب من إدارة خدمة العملاء
                            </button>
                        </li>

                    </ul>

                    <!-- Content -->
                    <div class="tab-content pt-3">

                        <!-- Content 1 -->
                        <div class="tab-pane fade show active" id="personal">
                            <h5>البيانات الشخصية</h5>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">نوع الطلب</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="requesttypeid" name="requesttypeid">
                                        <option value="">اختر</option>
                                        @foreach ($requestTypes as $type)
                                            <option value="{{ $type->requesttypeid }}">
                                                {{ $type->requesttypename }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="nationalIdSection" style="display:none;">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">الرقم القومي</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="national_id"
                                            name="ComplaintNationalID">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">النوع</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="gender" name="ComplainerGender"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">اسم العميل</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="ComplainerName">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">البريد الإلكتروني</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="ComplainerEmail">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">الهاتف</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="ComplainerPhone">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">المحافظة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="ComplaintGovernorate">
                                        <option value="">اختر المحافظة</option>
                                        @foreach ($govs as $gov)
                                            <option value="{{ $gov->govsid }}">
                                                {{ $gov->govname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- Content 2 -->
                        <div class="tab-pane fade" id="complaint">
                            <h5>تفاصيل الطلب</h5>

                            <!-- تاريخ الشكوى -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">تاريخ الشكوى</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="ComplaintDate">
                                </div>
                            </div>

                            <!-- القطاع -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">القطاع</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="sector_id">
                                        <option value="">اختر القطاع</option>
                                        @foreach ($sectors as $sector)
                                            <option value="{{ $sector->sector_id }}">
                                                {{ $sector->sector_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- مصدر الشكوى (multi-select) -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">مصدر الشكوى</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="comsources[]" multiple>
                                        @foreach ($comsources as $source)
                                            <option value="{{ $source->comsourcesid }}">
                                                {{ $source->comsourcesname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <!-- Governorate -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">المحافظة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="governorateSelect" name="ComplaintGovernorate">
                                        <option value="">اختر المحافظة</option>
                                        @foreach ($govs as $gov)
                                            <option value="{{ $gov->govsid }}">{{ $gov->govname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Office -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">المكتب</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="officeSelect" name="office">
                                        <option value="">اختر المكتب</option>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->ID }}" data-gov="{{ $office->FK_GOVT_CODE }}">
                                                {{ $office->REG_OFFIC_NAMA }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Save Button -->

                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-primary" type="submit">
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
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/complaints.js') }}"></script>
@endpush
