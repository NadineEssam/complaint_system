@extends('dashboard.layouts.app')

@section('title', 'الرد على الشكوى')

@push('headScripts')
<link href="{{ asset('assets/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/datatable.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="page-content-wrapper">
    <div class="page-content">

        <!-- 🔹 Breadcrumb -->
        <div class="page-breadcrumb d-md-flex align-items-center mb-4 pb-2 border-bottom" dir="rtl">
            <div class="pr-3">
                <nav>
                    <ol class="breadcrumb mb-0 p-0 shadow-none">
                        <li class="breadcrumb-item active text-primary font-weight-bold">
                            الردود على الشكوى #{{ $complaint->ComplaintID }}
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-secondary">
                                <i class="bx bx-home-alt"></i> الرئيسية
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- 🔹 Card -->
        <div class="card radius-15 shadow-sm border-0" dir="rtl">
            <div class="card-body">

                <!-- 🔹 Header -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary">
                        <i class="bx bx-message-square-detail ml-2"></i>
                        سجل الردود
                    </h4>

                    @if (PerUser('responses.create'))
                    <!-- <a href="{{ route('responses.create', $complaint->ComplaintID) }}"
                        class="btn btn-sm btn-success">
                        <i class="bx bx-plus"></i> إضافة رد
                    </a> -->

                    <a href="{{ route('responses.create', ['complaint_id' => $complaint->ComplaintID]) }}"
                       class="btn btn-sm btn-success">
                        <i class="bx bx-plus"></i>  إضافة رد
                    </a>
                    @endif
                </div>

                <hr class="d-none">

                <!-- 🔹 Your ORIGINAL table -->
                <div class="px-2 pb-3">
                    <div class="table-responsive">
                        {{ $dataTable->table(['class' => 'table text-center align-middle datatable-custom', 'style' => 'width:100%']) }}
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

<!-- @push('footerScripts')
<script src="{{ asset('assets/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script src="{{ asset('assets/datatable/js/jquery.dataTables.min.js') }}"></script> -->

@push('footerScripts')
<script src="{{ asset('assets/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script src="{{ asset('assets/datatable/js/jquery.dataTables.min.js') }}"></script>
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
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
                        window.LaravelDataTables["complaint_responses"].draw();
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
                        window.LaravelDataTables["complaint_responses"].draw();
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
<!-- <script src="{{ asset('assets/js/responses.js') }}"></script> -->
<!-- @endpush -->