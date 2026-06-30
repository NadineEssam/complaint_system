@extends('dashboard.layouts.app')

@section('title', 'مصادر الشكاوى')

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
                            مصادر الشكاوى
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
                        <i class="bx bx-git-branch ml-2"></i>
                        قائمة مصادر الشكاوى
                    </h4>

                    @if (PerUser('sources.create'))
                    <a href="{{ route('sources.create') }}"
                        class="btn btn-sm btn-success">
                        <i class="bx bx-plus"></i>
                        إضافة مصدر
                    </a>
                    @endif
                </div>

                <hr class="d-none">

                <!-- 🔹 Table -->
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

@push('footerScripts')
<script src="{{ asset('assets/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script src="{{ asset('assets/datatable/js/jquery.dataTables.min.js') }}"></script>
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
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
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    url: url,
                    success: function(msg) {
                        window.LaravelDataTables["com_sources"].draw();
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