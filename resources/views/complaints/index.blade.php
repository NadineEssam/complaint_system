@extends('layouts.app')

@section('title', 'الشكاوى')

@section('content')

<div class="pagetitle">
  <h1>الشكاوى</h1>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">

              <h5 class="card-title mb-0">قائمة الشكاوى</h5>

              <a href="{{ route('complaints.details') }}" class="btn btn-primary">
                   إضافة شكوى جديدة
              </a>

          </div>


          <!-- جدول -->
          <table id="complaintsTable" class="table table-striped text-center">
            <thead>
              <tr>
                <th>رقم الشكوى</th>
                <th>عنوان الشكوى</th>
                <th>اسم مقدم الشكوى</th>
                <th>رقم الهاتف</th>
                <th>تاريخ الشكوى</th>
                <th>الحالة</th>
                <th>إجراءات</th>
              </tr>
            </thead>
          </table>

        </div>
      </div>

    </div>
  </div>
</section>

@endsection
@push('scripts')
<script>
$(document).ready(function() {

    let table = $('#complaintsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('complaints.data') }}",
            data: function(d) {
                d.search = $('#search').val();
                d.status = $('#status').val();
                d.date = $('#date').val();
            }
        },

        columns: [
            { data: 'ComplaintID' },
            { data: 'ComplaintTitle' },
            { data: 'ComplainerName' },
            { data: 'ComplainerPhone' },
            { data: 'ComplaintDate' },
            { data: 'ComplaintStatus' },
            { data: 'action', orderable: true, searchable: true }
        ],

        language: {
            "search": "بحث:",
            "paginate": {
                "next": "التالي",
                "previous": "السابق"
            }
        }
    });

    // 🔥 filter button
    $('#filterBtn').click(function() {
        table.ajax.reload();
    });

});
</script>
@endpush