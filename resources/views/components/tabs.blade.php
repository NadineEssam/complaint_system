<div class="card">
  <div class="card-body">
    <h5 class="card-title">بيانات الشكوى</h5>

    <!-- Default Tabs -->
    <ul class="nav nav-tabs d-flex" id="complaintTabs" role="tablist">

      <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100 active"
                id="personal-tab"
                data-bs-toggle="tab"
                data-bs-target="#personal"
                type="button"
                role="tab">
          ال الشخصية لمقدم الطلب
        </button>
      </li>

      <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100"
                id="details-tab"
                data-bs-toggle="tab"
                data-bs-target="#details"
                type="button"
                role="tab">
          تفاصيل بيانات الطلب
        </button>
      </li>
    


    </ul>

    <div class="tab-content pt-3" id="complaintTabsContent">

      <div class="tab-pane fade show active" id="personal" role="tabpanel">
        {{-- بيانات الشخص --}}
        <p>هنا تحطي بيانات الشخص (الاسم - الرقم القومي - التليفون...)</p>
      </div>

      <div class="tab-pane fade" id="details" role="tabpanel">
        {{-- تفاصيل الشكوى --}}
        <p>هنا تحطي تفاصيل الشكوى (الوصف - التاريخ - الحالة...)</p>
      </div>

    </div>
  </div>
</div>