
@extends('dashboard.layouts.app')
@push('headScripts')
    <link href="{{ asset('assets/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatable.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
                <div class="pr-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 shadow-none">
                            <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page">الشكاوى
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-secondary"><i class="bx bx-home-alt"></i>
                                    الرئيسية</a>
                            </li>

                                                            </ol>
                                                        </nav>
                                                    </div>

                                                </div>

                                                <!--end breadcrumb-->
                                                <div class="card radius-15 shadow-sm border-0" dir="rtl" style="text-align: right;">
                                                    <div class="card-body">
                                                        <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                                            <h4 class="mb-0 text-primary"><i class="bx bx-shield-quarter ml-2"></i> إدارة الشكاوى
                                                            </h4>
                                                            <div>
                                                                @if (PerUser('complaints.create'))
                                                                <a href="{{ route('complaints.create') }}" class="btn btn-sm btn-success"><i
                                                                        class="bx bx-plus"></i> إضافة شكوي</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr class="d-none">
                                                        <div class="px-2 pb-3">
                                                            <div class="table-responsive">
                                                                {{-- Filter Bar --}}
                                                                <div class="p-3 mb-3 rounded-3 border" style="background: #f8f9fa;" dir="rtl">
                                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                                        <i class="bx bx-filter-alt text-primary fs-5"></i>
                                                                        <span class="fw-bold text-primary" style="font-size:14px;">تصفية الشكاوى</span>
                                                                    </div>
                                                                    <div class="row g-2">
                                                                        <div class="col-md-3 col-sm-6">
                                                                           
                                                                            <label class="form-label text-muted mb-1" style="font-size:12px;">
                                                                                <i class="bx bx-category me-1"></i> نوع الطلب
                                                                            </label>
                                                                            <select id="filter_reqtype" class="form-select form-select-sm shadow-none border-primary-subtle">
                                                                                <option value="">الكل</option>
                                                                                @foreach($reqTypes as $r)
                                                                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3 col-sm-6">
                                                                            <label class="form-label text-muted mb-1" style="font-size:12px;">
                                                                                <i class="bx bx-map me-1"></i> المحافظة
                                                                            </label>
                                                                            <select id="filter_gov" class="form-select form-select-sm shadow-none border-primary-subtle">
                                                                                <option value="">الكل</option>
                                                                                @foreach($govs as $gov)
                                                                                <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3 col-sm-6">
                                                                            <label class="form-label text-muted mb-1" style="font-size:12px;">
                                                                                <i class="bx bx-info-circle me-1"></i> الحالة
                                                                            </label>
                                                                            <select id="filter_status" class="form-select form-select-sm shadow-none border-primary-subtle">
                                                                                <option value="">الكل</option>
                                                                                @foreach($statuses as $s)
                                                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3 col-sm-6">
                                                                             <label class="form-label text-muted mb-1" style="font-size:12px;">
                                                                                <i class="bx bx-male-female me-1"></i> الجنس
                                                                            </label>
                                                                            <select id="filter_gender" class="form-select form-select-sm shadow-none border-primary-subtle">
                                                                                <option value="">الكل</option>
                                                                                @foreach($genders as $g)
                                                                                <option value="{{ $g['id'] }}">{{ $g['name'] }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start mt-2">
                                                                        <button id="reset_filters" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                                                                            <i class="bx bx-reset"></i> إعادة تعيين
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                {{ $dataTable->table(['class' => 'table text-center align-middle datatable-custom', 'style' => 'width:100%']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endsection

                                        @push('footerScripts')
                                        <script src="{{ asset('assets/vendor/sweetalert/sweetalert.all.js') }}"></script>
                                        <script src="{{ asset('assets/datatable/js/jquery.dataTables.min.js') }}"></script>
                                        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
                                        <script>
                                            function applyComplaintFilters() {
                                                var table = window.LaravelDataTables["roles"];

                                                var params = {
                                                    gender_filter: $('#filter_gender').val(),
                                                    gov_filter: $('#filter_gov').val(),
                                                    status_filter: $('#filter_status').val(),
                                                    reqtype_filter: $('#filter_reqtype').val(),
                                                };

                                                table.settings()[0].ajax = {
                                                    url: '{{ route("complaints.index") }}',
                                                    data: function(d) {
                                                        $.extend(d, params);
                                                    }
                                                };

                                                table.ajax.reload();
                                            }

                                            $(document).on('change', '#filter_gender, #filter_gov, #filter_status, #filter_reqtype',
                                                applyComplaintFilters
                                            );
                                            // Reset all filters
                                            $('#reset_filters').on('click', function() {
                                                $('#filter_gender, #filter_gov, #filter_status, #filter_reqtype').val('');
                                                applyComplaintFilters();
                                            });
                                        </script>
                                        <script>
                                            $(document).on('click', '.export-type', function(e) {
                                                e.preventDefault();
                                                let form = $(this).parent().parent().find('form');
                                                form.find('[name="export_type"]').val($(this).attr('data-type'));
                                                form.submit();
                                            });

                                            function checkMultiDeleteButton() {
                                                if ($(".provider_service-checkbox").is(':checked')) {
                                                    $(".delete-selected").removeClass('disabled');
                                                    $(".export-selected,.export-types").removeClass('disabled');
                                                } else {
                                                    $(".delete-selected").addClass('disabled');
                                                    $(".export-selected,.export-types").addClass('disabled');

                                                }
                                            }
                                            checkMultiDeleteButton();
                                            $(document).on('click', '.delete-selected', function() {
                                                let IDS = [];
                                                $('.provider_service-checkbox:checked').each(function() {
                                                    IDS.push($(this).val());
                                                });
                                                Swal.fire({
                                                    title: 'هل أنت متأكد من عملية الحذف؟',
                                                    text: "لن تتمكن من التراجع عن هذا الإجراء!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
                                                    confirmButtonText: 'نعم، احذف',
                                                    cancelButtonText: 'إلغاء'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $.ajax({
                                                            type: "DELETE",
                                                            url: "{{-- route('advertising.multi_destroy') --}}",
                                                            data: {
                                                                IDS,
                                                                '_token': '{{ csrf_token() }}'
                                                            },
                                                            success: function(msg) {
                                                                window.LaravelDataTables["roles"].draw();
                                                                Swal.fire(
                                                                    'تم الحذف!',
                                                                    msg.message,
                                                                    msg.success ? 'success' : 'error'
                                                                );
                                                            }
                                                        });

                                                    }
                                                });
                                            });

                                            function addSelectedCount() {
                                                $(".selectedCount").text($(".provider_service-checkbox:checked").length);
                                                let IDS = [];
                                                $('.provider_service-checkbox:checked').each(function() {
                                                    IDS.push($(this).val());
                                                });
                                                $("#exportIDS").val(IDS);
                                            }
                                            $(document).on('change', '#selectAllCheckbox', function() {
                                                $('.provider_service-checkbox').prop('checked', $(this).is(':checked'));
                                                checkMultiDeleteButton();
                                                addSelectedCount();
                                            });
                                            $(document).on('change', '.provider_service-checkbox', function() {
                                                checkMultiDeleteButton();
                                                addSelectedCount();
                                            });

                                            $(document).on('click', '.delete-this', function(e) {
                                                e.preventDefault();
                                                let el = $(this);
                                                let url = el.attr('data-url');
                                                let id = el.attr('data-id');
                                                Swal.fire({
                                                    title: 'هل أنت متأكد من عملية الحذف؟',
                                                    text: "لن تتمكن من التراجع عن هذا الإجراء!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
                                                    confirmButtonText: 'نعم، احذف',
                                                    cancelButtonText: 'إلغاء'
                                                }).then((result) => {
                                                    /* Read more about isConfirmed, isDenied below */
                                                    if (result.isConfirmed) {
                                                        $.ajax({
                                                            type: "DELETE",
                                                            data: {
                                                                '_token': '{{ csrf_token() }}'
                                                            },
                                                            url: url,
                                                            success: function(msg) {
                                                                window.LaravelDataTables["roles"].draw();
                                                                Swal.fire(
                                                                    'تم الحذف!',
                                                                    msg.message,
                                                                    msg.success ? 'success' : 'error'
                                                                );
                                                            }
                                                        });

                                                    }
                                                });
                                            });
                                        </script>
                                        @endpush