@extends('dashboard.layouts.app')
@push('headScripts')
    <style>
        .page-breadcrumb .breadcrumb {
            background-color: transparent;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            display: inline-block;
            padding-right: 0.5rem;
            padding-left: 0.5rem;
            color: #6c757d;
            content: "/";
        }
    </style>

    <style>
        body {
            margin-top: 40px;
        }

        .stepwizard-step p {
            margin-top: 10px;
        }

        .stepwizard-row {
            display: table-row;
        }

        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }

        .stepwizard-step button[disabled] {
            opacity: 1 !important;
            filter: alpha(opacity=100) !important;
        }

        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-order: 0;

        }

        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }
    </style>
@endpush
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
                <div class="pr-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 shadow-none">
                            <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page">
                                {{ isset($complaint) ? 'تعديل الشكوى: ' . $complaint->ComplainerName : 'إضافة شكوى جديدة' }}
                            </li>

                            <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}" class="text-secondary"><i
                                        class="bx bx-shape-polygon"></i> الشكاوى</a></li>

                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-secondary"><i
                                        class="bx bx-home-alt"></i> الرئيسية</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card radius-15 shadow-sm border-0" dir="rtl" style="text-align: right;">
                <div class="card-body">
                    <div class="card-header bg-white mb-4 d-flex align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold"><i class="bx bx-shield-quarter ml-2"></i>
                            {{ isset($complaint) ? 'تعديل بيانات الشكوى' : 'بيانات  شكوى جديدة' }}</h5>
                    </div>



                    <div class="container">
                        <div class="stepwizard">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                    <p>Step 1</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-2" type="button" class="btn btn-default btn-circle"
                                        disabled="disabled">2</a>
                                    <p>Step 2</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-3" type="button" class="btn btn-default btn-circle"
                                        disabled="disabled">3</a>
                                    <p>Step 3</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" role="form"
                            action="{{ isset($complaint) ? route('complaints.update', ['complaint' => $complaint]) : route('complaints.store') }}">
                            @if (isset($complaint))
                                @method('PUT')
                            @endif
                            @csrf
                            <div class="row setup-content" id="step-1">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        <h3> Step 1</h3>
                                        <div class="row mb-3">
                                            <label for="name" class="col-sm-2 col-form-label font-weight-bold">اسم
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    id="name"
                                                    value="{{ isset($complaint) ? $complaint->ComplainerName : old('name') }}"
                                                    placeholder="أدخل اسم ">
                                                @error('name')
                                                    <span class="invalid-feedback" copmlaint="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Last Name</label>
                                            <input maxlength="100" type="text" required="required" class="form-control"
                                                placeholder="Enter Last Name" />
                                        </div>
                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                            type="button">Next</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row setup-content" id="step-2">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        <h3> Step 2</h3>
                                        <div class="form-group">
                                            <label class="control-label">Company Name</label>
                                            <input maxlength="200" type="text" required="required" class="form-control"
                                                placeholder="Enter Company Name" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Company Address</label>
                                            <input maxlength="200" type="text" required="required" class="form-control"
                                                placeholder="Enter Company Address" />
                                        </div>
                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                            type="button">Next</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row setup-content" id="step-3">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        <h3> Step 3</h3>
                                        <div class="row mb-3 mt-4">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i
                                                        class="bx bx-save ml-1"></i> finish حفظ البيانات</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection

@push('footerScripts')
    <script>
        $(document).ready(function() {

            var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn');

            allWells.hide();

            navListItems.click(function(e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                    $item = $(this);

                if (!$item.hasClass('disabled')) {
                    navListItems.removeClass('btn-primary').addClass('btn-default');
                    $item.addClass('btn-primary');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function() {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next()
                    .children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid)
                    nextStepWizard.removeAttr('disabled').trigger('click');
            });

            $('div.setup-panel div a.btn-primary').trigger('click');
        });
    </script>
@endpush
