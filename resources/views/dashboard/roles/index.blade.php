@extends('dashboard.layouts.app')
@push('headScripts')
    <link href="{{ asset('assets/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatable.css') }}" rel="stylesheet" type="text/css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ================= GLOBAL ================= */
        .roles-page, .roles-page * {
            font-family: 'Cairo', 'Tahoma', sans-serif;
            font-size: 13px;
        }

        .roles-page {
            background: #f7f8fa;
            padding: 24px;
            border-radius: 16px;
        }

        /* ================= BREADCRUMB ================= */
        .roles-page .breadcrumb {
            margin-bottom: 0;
            background: transparent;
            padding: 0;
        }

        .roles-page .breadcrumb-item,
        .roles-page .breadcrumb-item a {
            font-size: 13px;
            color: #98a2b3;
            text-decoration: none;
        }

        .roles-page .breadcrumb-item.active {
            color: #1f2937;
            font-weight: 700;
        }

        /* ================= CARD ================= */
        .roles-page .main-card {
            border: 1px solid #eef0f3 !important;
            border-radius: 14px !important;
            background: #fff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        /* ================= CARD HEADER ================= */
        .roles-page .card-top-header {
            padding: 16px 20px;
            border-bottom: 1px solid #eef0f3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }

        .roles-page .card-top-header h4 {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .roles-page .card-top-header h4 i {
            color: #3b76e0;
            font-size: 16px;
        }

        /* ================= ADD BUTTON ================= */
        .roles-page .btn-add {
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 8px;
            background: #eafaf0;
            color: #2f9e63;
            border: 1px solid #c6edd8;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: background .15s;
        }

        .roles-page .btn-add:hover {
            background: #d4f5e4;
            color: #1f7a4b;
        }
    </style>
@endpush
@section('content')

<div class="roles-page" dir="rtl">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">الأدوار</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="bx bx-home-alt"></i> الرئيسية
                    </a>
                </li>
            </ol>
        </nav>
    </div>

    {{-- ================= MAIN CARD ================= --}}
    <div class="main-card">

        {{-- Card Header --}}
        <div class="card-top-header">
            <h4>
                <i class="bx bx-shield-quarter"></i>
                إدارة الأدوار والصلاحيات
            </h4>
            @if (PerUser('roles.create'))
            <a href="{{ route('roles.create') }}" class="btn-add">
                <i class="bx bx-plus"></i>
                إضافة دور
            </a>
            @endif
        </div>

        {{-- Body --}}
        <div class="p-3">
            <div class="table-responsive">
                {{ $dataTable->table(['class' => 'table text-center align-middle datatable-custom', 'style' => 'width:100%']) }}
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
                        data: {'_token': '{{ csrf_token() }}' },
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