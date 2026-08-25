@extends('dashboard.layouts.app')
@push('headScripts')
    <link href="{{ asset('assets/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatable.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  /* ================= GLOBAL ================= */
  .complaints-page, .complaints-page * {
    font-family: 'Cairo', 'Tahoma', sans-serif;
    font-size: 13px;
  }

  .complaints-page {
    background: #f7f8fa;
    padding: 24px;
    border-radius: 16px;
  }

  /* ================= BREADCRUMB ================= */
  .complaints-page .breadcrumb {
    margin-bottom: 0;
    background: transparent;
    padding: 0;
  }

  .complaints-page .breadcrumb-item,
  .complaints-page .breadcrumb-item a {
    font-size: 13px;
    color: #98a2b3;
    text-decoration: none;
  }

  .complaints-page .breadcrumb-item.active {
    color: #1f2937;
    font-weight: 700;
  }

  /* ================= CARD ================= */
  .complaints-page .main-card {
    border: 1px solid #eef0f3 !important;
    border-radius: 14px !important;
    background: #fff;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }

  /* ================= CARD HEADER ================= */
  .complaints-page .card-top-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eef0f3;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
  }

  .complaints-page .card-top-header h4 {
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .complaints-page .card-top-header h4 i {
    color: #3b76e0;
    font-size: 16px;
  }

  .complaints-page .btn-add {
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

  .complaints-page .btn-add:hover {
    background: #d4f5e4;
    color: #1f7a4b;
  }

  /* ================= FILTER BAR ================= */
  .complaints-page .filter-bar {
    background: #f7f8fa;
    border: 1px solid #eef0f3;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 16px;
  }

  .complaints-page .filter-bar .filter-title {
    font-size: 13px;
    font-weight: 700;
    color: #3b76e0;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
  }

  .complaints-page .filter-bar .filter-title i {
    font-size: 15px;
  }

  .complaints-page .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .complaints-page .form-select {
    font-size: 13px;
    font-family: 'Cairo', 'Tahoma', sans-serif;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    color: #1f2937;
    padding: 6px 10px;
    background-color: #fff;
    background-position: left 0.75rem center !important;
    padding-left: 2.25rem !important;
    padding-right: 0.75rem !important;
    box-shadow: none !important;
    transition: border-color .15s;
  }

  .complaints-page .form-select:focus {
    border-color: #3b76e0;
    box-shadow: 0 0 0 0.18rem rgba(59,118,224,.13) !important;
  }

  .complaints-page .btn-reset {
    font-size: 13px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    background: #fff;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background .15s;
  }

  .complaints-page .btn-reset:hover {
    background: #f3f4f6;
    color: #374151;
  }
</style>

<div class="complaints-page" dir="rtl">

  {{-- ================= BREADCRUMB ================= --}}
  <div class="mb-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">البيان</li>
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
        <i class="bx bx-list-ul"></i>
        إدارة البيان
      </h4>
      @if (PerUser('complaints.create'))
      <a href="{{ route('complaints.create') }}" class="btn-add">
        <i class="bx bx-plus"></i>
        إضافة بيان
      </a>
      @endif
    </div>

    {{-- Body --}}
    <div class="p-3">

      {{-- ================= FILTER BAR ================= --}}
      <div class="filter-bar">

        <div class="filter-title">
          <i class="bx bx-filter-alt"></i>
          تصفية البيان
        </div>

        <div class="row g-2">

          <div class="col-md-3 col-sm-6">
            <label class="form-label">
              <i class="bx bx-category"></i> نوع الطلب
            </label>
            <select id="filter_reqtype" class="form-select form-select-sm">
              <option value="">الكل</option>
              @foreach($reqTypes as $r)
              <option value="{{ $r->id }}">{{ $r->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6">
            <label class="form-label">
              <i class="bx bx-buildings"></i> الفرع
            </label>
            <select id="filter_office" class="form-select form-select-sm">
              <option value="">الكل</option>
              @foreach($offices as $o)
              <option value="{{ $o->id }}">{{ $o->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6">
            <label class="form-label">
              <i class="bx bx-info-circle"></i> الحالة
            </label>
            <select id="filter_status" class="form-select form-select-sm">
              <option value="">الكل</option>
              @foreach($statuses as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6">
            <label class="form-label">
              <i class="bx bx-male-female"></i> الجنس
            </label>
            <select id="filter_gender" class="form-select form-select-sm">
              <option value="">الكل</option>
              @foreach($genders as $g)
              <option value="{{ $g['id'] }}">{{ $g['name'] }}</option>
              @endforeach
            </select>
          </div>

        </div>

        <div class="d-flex justify-content-start mt-3">
          <button id="reset_filters" class="btn-reset">
            <i class="bx bx-reset"></i> إعادة تعيين
          </button>
        </div>

      </div>

      {{-- ================= TABLE ================= --}}
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
    function applyComplaintFilters() {
        var table = window.LaravelDataTables["roles"];

        var params = {
            gender_filter: $('#filter_gender').val(),
            gov_filter: $('#filter_gov').val(),
            office_filter:  $('#filter_office').val(),
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
    $(document).on('change', '#filter_gender, #filter_office, #filter_status, #filter_reqtype',
            applyComplaintFilters
        );
        $('#reset_filters').on('click', function() {
            $('#filter_gender, #filter_office, #filter_status, #filter_reqtype').val('');
            applyComplaintFilters();
        });
</script>
@endpush