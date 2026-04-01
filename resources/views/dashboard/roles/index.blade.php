@extends('dashboard.layouts.app')
@push('headScripts')
    <link href="{{ asset('assets/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-md-flex align-items-center mb-3">
                {{-- <div class="breadcrumb-title pr-3">{{ __('Roles') }}</div> --}}
                <div class="pl-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i> {{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{ __('Roles') }}</li>
                        </ol>
                    </nav>
                </div>
                @if(PerUser('roles.create'))
                <div class="ml-auto">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fadeIn animated bx bx-message-square-add"></i> {{ __('Create :type',['type'=>__('Role')]) }}</a>
                </div>
                @endif
            </div>
            <!--end breadcrumb-->
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">{{ __('Roles') }}</h4>
                    </div>
                    <hr>
{{--                    <div class="table-responsive">--}}
{{--                        <table class="table table-bordered table-striped mb-0"></table>--}}
{{--                    </div>--}}
                    <div class="table-responsive">
                        {{ $dataTable->table(['class' => 'table text-center table-bordered table-striped mb-0','style'=>'width:100%']) }}
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
        $(document).on('click','.export-type',function(e){
            e.preventDefault();
            let form=$(this).parent().parent().find('form');
            form.find('[name="export_type"]').val($(this).attr('data-type'));
            form.submit();
        });
        function checkMultiDeleteButton(){
           if($(".provider_service-checkbox").is(':checked')){
               $(".delete-selected").removeClass('disabled');
               $(".export-selected,.export-types").removeClass('disabled');
           }else{
               $(".delete-selected").addClass('disabled');
               $(".export-selected,.export-types").addClass('disabled');

           }
        }
        checkMultiDeleteButton();
        $(document).on('click','.delete-selected',function(){
            let IDS=[];
            $('.provider_service-checkbox:checked').each(function(){
                IDS.push($(this).val());
            });
            Swal.fire({
                title: '{{ __('Do you really want to delete this?') }}',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes') }}',
                cancelButtonText: '{{ __('No') }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{-- route('advertising.multi_destroy') --}}",
                        data:{IDS},
                        success: function (msg) {
                            window.LaravelDataTables["roles"].draw();
                            Swal.fire(msg.message, '', msg.success?'success':'error');
                        }
                    });

                }
            });
        });
        function addSelectedCount(){
            $(".selectedCount").text($(".provider_service-checkbox:checked").length);
            let IDS=[];
            $('.provider_service-checkbox:checked').each(function(){
                IDS.push($(this).val());
            });
            $("#exportIDS").val(IDS);
        }
        $(document).on('change','#selectAllCheckbox',function(){
            $('table#advertising tbody input[type="checkbox"]').prop('checked',$(this).is(':checked'));
            checkMultiDeleteButton();
            addSelectedCount();
        });;
        $(document).on('change','.provider_service-checkbox',function (){
            checkMultiDeleteButton();
            addSelectedCount();
        });
        @if(PerUser('provider_services.destroy'))

        $(document).on('click','.delete-this',function(e){
            e.preventDefault();
            let el=$(this);
            let url=el.attr('data-url');
            let id=el.attr('data-id');
            Swal.fire({
                title: '{{ __('Do you really want to delete this?') }}',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes') }}',
                cancelButtonText: '{{ __('No') }}',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function (msg) {
                            window.LaravelDataTables["roles"].draw();
                            Swal.fire(msg.message, '', msg.success?'success':'error');
                        }
                    });

                }
            });
        });
        @endif
    </script>
    <script>
        $(document).on('click','.delete-this',function(e){
            e.preventDefault();
            let el=$(this);
            let url=el.attr('data-url');
            let id=el.attr('data-id');
            Swal.fire({
                title: '{{ __('Do you really want to delete this?') }}',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes') }}',
                cancelButtonText: '{{ __('No') }}',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function (msg) {
                            window.LaravelDataTables["roles"].draw();
                            Swal.fire(msg.message, '', msg.success?'success':'error');
                        }
                    });

                }
            });
        });
    </script>

@endpush
