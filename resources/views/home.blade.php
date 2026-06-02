@extends('dashboard.layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
/* ================= ROOT ================= */
:root{
  --primary:#4f8cff;
  --primary-light:#eef4ff;
  --success:#19c37d;
  --warning:#ffb547;
  --danger:#ff5d73;
  --dark:#1f2937;
  --gray:#6b7280;
  --border:#edf1f7;
  --card:#ffffff;
  --bg:#f4f7fc;
}

/* ================= GLOBAL ================= */
.section.dashboard{
  background: var(--bg);
  padding: 24px;
  border-radius: 24px;
}

/* ================= PAGE TITLE ================= */
.pagetitle h1{
  font-size: 28px;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 6px;
}

.breadcrumb{
  margin-bottom: 0;
}

.breadcrumb-item,
.breadcrumb-item a{
  color: #8b95a7;
  font-size: 14px;
  text-decoration: none;
}

/* ================= CARDS ================= */
.card{
  border: none !important;
  border-radius: 24px !important;
  overflow: hidden;
  background: var(--card);
  box-shadow: 0 10px 40px rgba(15,23,42,0.05);
  transition: 0.3s ease;
}

.card:hover{
  transform: translateY(-4px);
  box-shadow: 0 18px 50px rgba(15,23,42,0.08);
}

.card-title{
  font-size: 15px;
  font-weight: 700;
  color: var(--gray);
  margin-bottom: 18px;
}

/* ================= KPI CARDS ================= */
.kpi-card{
  position: relative;
  padding: 4px;
}

.kpi-card .card-body{
  padding: 24px;
}

.card-icon{
  width: 65px;
  height: 65px;
  min-width: 65px;
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  color: #fff;
  box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

.bg-primary-gradient{
  background: linear-gradient(135deg,#5b8cff,#7c4dff);
}

.bg-success-gradient{
  background: linear-gradient(135deg,#00c896,#00e5a8);
}

.bg-warning-gradient{
  background: linear-gradient(135deg,#ffb547,#ffcc73);
}

.bg-danger-gradient{
  background: linear-gradient(135deg,#ff5d73,#ff8a65);
}

.counter{
  font-size: 32px;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 0;
}

.counter-label{
  color: #9ca3af;
  font-size: 13px;
  font-weight: 500;
}

/* ================= HEADERS ================= */
.custom-header{
  padding: 18px 22px;
  font-size: 16px;
  font-weight: 700;
  border-bottom: 1px solid var(--border);
  background: #fff;
  color: var(--dark);
  display:flex;
  align-items:center;
  justify-content:space-between;
}

.custom-header i{
  font-size: 18px;
  opacity: .8;
}

/* ================= CHART WRAPPER ================= */
.chart-box{
  padding: 10px;
}

/* ================= GOVERNORATE ================= */
.gov-header{
  background: linear-gradient(135deg,#00c896,#00d9a6);
  color:#fff;
}

/* ================= DARK MODE ================= */
.dark-mode{
  background:#111827;
  color:#fff;
}

.dark-mode .card{
  background:#1f2937;
}

.dark-mode .card-title,
.dark-mode .counter,
.dark-mode .custom-header{
  color:#fff;
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){

  .section.dashboard{
    padding: 15px;
  }

  .pagetitle{
    flex-direction: column;
    align-items: flex-start !important;
    gap: 10px;
  }

  .counter{
    font-size: 26px;
  }

  .card-icon{
    width: 55px;
    height: 55px;
    font-size: 22px;
  }

}
</style>

<section class="section dashboard">

{{-- ================= HEADER ================= --}}
<div class="pagetitle mb-4 d-flex justify-content-between align-items-center">

  <div>
    <h1>إحصائيات الشكاوى</h1>

    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          إحصائيات الشكاوى
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('home') }}">الرئيسية</a>
        </li>

      </ol>
    </nav>
  </div>

</div>

{{-- ================= KPI ================= --}}
<div class="row g-4 mb-4">

  {{-- TOTAL --}}
  <div class="col-xxl-3 col-md-6">
    <div class="card kpi-card">

      <div class="card-body">

        <div class="d-flex align-items-center justify-content-between">

          <div>
            <div class="card-title mb-2">
              إجمالي الشكاوى
            </div>

            <h2 class="counter">
              {{ $total }}
            </h2>

            <div class="counter-label">
              جميع الشكاوى اليوم
            </div>
          </div>

          <div class="card-icon bg-primary-gradient">
            <i class="bi bi-collection"></i>
          </div>

        </div>

      </div>

    </div>
  </div>

  {{-- SOLVED --}}
  <div class="col-xxl-3 col-md-6">
    <div class="card kpi-card">

      <div class="card-body">

        <div class="d-flex align-items-center justify-content-between">

          <div>
            <div class="card-title mb-2">
              تم الحل
            </div>

            <h2 class="counter">
              {{ $status24Total }}
            </h2>

            <div class="counter-label">
              الشكاوى المغلقة
            </div>
          </div>

          <div class="card-icon bg-success-gradient">
            <i class="bi bi-check-circle"></i>
          </div>

        </div>

      </div>

    </div>
  </div>

  {{-- PENDING --}}
  <div class="col-xxl-3 col-md-6">
    <div class="card kpi-card">

      <div class="card-body">

        <div class="d-flex align-items-center justify-content-between">

          <div>
            <div class="card-title mb-2">
              قيد المعالجة
            </div>

            <h2 class="counter">
              {{ $total - $status24Total }}
            </h2>

            <div class="counter-label">
              تحتاج متابعة
            </div>
          </div>

          <div class="card-icon bg-warning-gradient">
            <i class="bi bi-hourglass-split"></i>
          </div>

        </div>

      </div>

    </div>
  </div>

  {{-- GOV --}}
  <div class="col-xxl-3 col-md-6">
    <div class="card kpi-card">

      <div class="card-body">

        <div class="d-flex align-items-center justify-content-between">

          <div>
            <div class="card-title mb-2">
              المحافظات
            </div>

            <h2 class="counter">
              {{ $govStats->count() }}
            </h2>

            <div class="counter-label">
              عدد المحافظات
            </div>
          </div>

          <div class="card-icon bg-danger-gradient">
            <i class="bi bi-geo-alt"></i>
          </div>

        </div>

      </div>

    </div>
  </div>

</div>

{{-- ================= CHARTS ================= --}}
<div class="row g-4">

  {{-- LEFT --}}
  <div class="col-lg-8">

    {{-- TYPES --}}
    <div class="card mb-4">

      <div class="custom-header">
        <span>📊 الشكاوى حسب النوع</span>
        <i class="bi bi-bar-chart-fill"></i>
      </div>

      <div class="card-body chart-box">
        <div id="reportsChart"></div>
      </div>

    </div>

    {{-- STATUS --}}
    <div class="card">

      <div class="custom-header">
        <span>📈 الشكاوى حسب الحالة</span>
        <i class="bi bi-graph-up"></i>
      </div>

      <div class="card-body chart-box">
        <div id="statusChart"></div>
      </div>

    </div>

  </div>

  {{-- RIGHT --}}
  <div class="col-lg-4">

    {{-- TRAFFIC --}}
    <div class="card mb-4">

      <div class="custom-header">
        <span>🎯 توزيع الشكاوى</span>
        <i class="bi bi-pie-chart-fill"></i>
      </div>

      <div class="card-body chart-box">
        <div id="trafficChart"></div>
      </div>

    </div>

    {{-- CLOSE REASON --}}
    <div class="card">

      <div class="custom-header">
        <span>📌 أسباب الإغلاق</span>
        <i class="bi bi-ui-checks-grid"></i>
      </div>

      <div class="card-body chart-box">
        <div id="closeReasonChart"></div>
      </div>

    </div>

  </div>

</div>

{{-- ================= DEPARTMENTS ================= --}}
<div class="row mt-4">

  <div class="col-12">

    <div class="card">

      <div class="custom-header">
        <span>🏢 الشكاوى حسب الإدارات</span>
        <i class="bi bi-buildings"></i>
      </div>

      <div class="card-body">
        <div id="departmentChart" style="height:450px;"></div>
      </div>

    </div>

  </div>

</div>

{{-- ================= GOVERNORATES ================= --}}
<div class="row mt-4">

  <div class="col-12">

    <div class="card border-0 shadow-sm overflow-hidden">

      <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

          <div>
            <h4 class="mb-1 fw-bold">
              🗺️ الشكاوى حسب المحافظات
            </h4>

            <p class="text-muted mb-0 small">
              توزيع عدد الشكاوى على المحافظات
            </p>
          </div>

          <div class="rounded-circle d-flex align-items-center justify-content-center"
               style="
                 width:55px;
                 height:55px;
                 background:#eef4ff;
                 color:#5b8cff;
                 font-size:22px;
               ">
            <i class="bi bi-map"></i>
          </div>

        </div>

        <div id="govChart"></div>

      </div>

    </div>

  </div>

</div>

</section>

@endsection

@push('footerScripts')

<script>
document.addEventListener("DOMContentLoaded", () => {

  // ================= COUNTER ANIMATION =================
  document.querySelectorAll('.counter').forEach(el => {

    let end = parseInt(el.innerText);
    let start = 0;
    let step = Math.ceil(end / 40);

    let interval = setInterval(() => {

      start += step;

      if(start >= end){
        el.innerText = end;
        clearInterval(interval);
      }else{
        el.innerText = start;
      }

    }, 20);

  });

  // ================= DATA =================
  const typeLabels = @json($requestTypesStats->pluck('requesttypename'));
  const typeData = @json($requestTypesStats->pluck('complaints_count'));

  const statusLabels = @json($statusStats->map(fn($s) => $s->status->statusText ?? 'غير معروف')->values());
  const statusData = @json($statusStats->pluck('total'));

  const deptLabels = @json($departmentsStats->pluck('department_name'));
  const deptCounts = @json($departmentsStats->pluck('complaints_count'));

  const govLabels = @json(collect($govStats)->pluck('name'));
  const govData = @json(collect($govStats)->pluck('total'));

  const closeData = @json($closeReasonStats);

  // ================= REPORTS CHART =================
  new ApexCharts(document.querySelector("#reportsChart"), {

    series: [{
      name: 'عدد الشكاوى',
      data: typeData
    }],

    chart: {
      type: 'bar',
      height: 360,
      toolbar: { show: false }
    },

    colors: ['#5b8cff'],

    plotOptions: {
      bar: {
        borderRadius: 10,
        columnWidth: '45%'
      }
    },

    dataLabels: {
      enabled: false
    },

    grid: {
      borderColor: '#f1f1f1'
    },

    xaxis: {
      categories: typeLabels
    }

  }).render();

  // ================= STATUS CHART =================
  new ApexCharts(document.querySelector("#statusChart"), {

    series: [{
      name: 'الحالات',
      data: statusData
    }],

    chart: {
      type: 'area',
      height: 350,
      toolbar: { show: false }
    },

    colors: ['#00c896'],

    stroke: {
      curve: 'smooth',
      width: 4
    },

    fill: {
      type: 'gradient',
      gradient: {
        opacityFrom: 0.5,
        opacityTo: 0.05
      }
    },

    dataLabels: {
      enabled: false
    },

    xaxis: {
      categories: statusLabels
    }

  }).render();

  // ================= TRAFFIC =================
  new ApexCharts(document.querySelector("#trafficChart"), {

    series: typeData,

    chart: {
      type: 'donut',
      height: 340
    },

    labels: typeLabels,

    colors: [
      '#5b8cff',
      '#00c896',
      '#ffb547',
      '#ff5d73',
      '#7c4dff',
      '#00d4ff'
    ],

    legend: {
      position: 'bottom'
    },

    plotOptions: {
      pie: {
        donut: {
          size: '72%',
          labels: {
            show: true,
            total: {
              show: true,
              label: 'الإجمالي',
              formatter: function (w) {
                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
              }
            }
          }
        }
      }
    }

  }).render();

  // ================= DEPARTMENT =================
  new ApexCharts(document.querySelector("#departmentChart"), {

    series: [{
      name: 'عدد الشكاوى',
      data: deptCounts
    }],

    chart: {
      type: 'line',
      height: 450,
      toolbar: { show: false }
    },

    colors: ['#7c4dff'],

    stroke: {
      curve: 'smooth',
      width: 4
    },

    markers: {
      size: 5
    },

    xaxis: {
      categories: deptLabels
    }

  }).render();

  // ================= GOVERNORATE =================
 new ApexCharts(document.querySelector("#govChart"), {
  series: [
    {
      name: "عدد الشكاوى",
      data: govData,
    },
  ],

  chart: {
    type: "bar",
    height: 520,
    background: "transparent",
    toolbar: {
      show: false,
    },
    fontFamily: "Cairo, sans-serif",
  },

  colors: ["#14b8a6"],

  theme: {
    mode: "light",
  },

  grid: {
    borderColor: "#e5e7eb",
    strokeDashArray: 4,
    xaxis: {
      lines: {
        show: true,
      },
    },
    yaxis: {
      lines: {
        show: false,
      },
    },
    padding: {
      left: 20,
      right: 20,
      top: 10,
      bottom: 10,
    },
  },

  plotOptions: {
    bar: {
      horizontal: true,
      borderRadius: 14,
      borderRadiusApplication: "end",
      barHeight: "58%",
      distributed: true,
      dataLabels: {
        position: "top",
      },
    },
  },

  dataLabels: {
    enabled: true,
    offsetX: 25,
    style: {
      fontSize: "13px",
      fontWeight: "700",
      colors: ["#111827"],
    },
    formatter: function (val) {
      return val;
    },
  },

  stroke: {
    show: true,
    width: 1,
    colors: ["#ffffff"],
  },

  xaxis: {
    categories: govLabels,

    axisBorder: {
      show: false,
    },

    axisTicks: {
      show: false,
    },

    labels: {
      style: {
        colors: "#6b7280",
        fontSize: "12px",
        fontWeight: 500,
      },
    },
  },

  yaxis: {
    labels: {
      align: "left",
     
      offsetX: 85,
      style: {
        fontSize: "16px",
        fontWeight: 600,
        colors: "#0f172a",
      },
    },
  },

  tooltip: {
    theme: "light",
    style: {
      fontSize: "13px",
      fontFamily: "Cairo, sans-serif",
    },
  },

  legend: {
    show: false,
  },

  states: {
    hover: {
      filter: {
        type: "lighten",
        value: 0.08,
      },
    },
  },

  responsive: [
    {
      breakpoint: 768,
      options: {
        chart: {
          height: 420,
        },
        yaxis: {
          labels: {
            style: {
              fontSize: "12px",
            },
          },
        },
      },
    },
  ],
}).render();

  // ================= CLOSE REASON =================
  new ApexCharts(document.querySelector("#closeReasonChart"), {

    series: closeData.map(i => i.total),

    chart: {
      type: 'donut',
      height: 340
    },

    labels: closeData.map(i => i.name),

    colors: [
      '#00c896',
      '#5b8cff',
      '#ffb547',
      '#ff5d73',
      '#7c4dff'
    ],

    legend: {
      position: 'bottom'
    },

    plotOptions: {
      pie: {
        donut: {
          size: '72%',
          labels: {
            show: true,
            total: {
              show: true,
              label: 'الإجمالي',
              formatter: function (w) {
                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
              }
            }
          }
        }
      }
    }

  }).render();

});
</script>

@endpush