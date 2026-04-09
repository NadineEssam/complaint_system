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
          <h5 class="card-title">قائمة الشكاوى</h5>


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


{{-- 🔥 JS goes HERE (outside section content) --}}
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
            { data: 'action', orderable: false, searchable: false }
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