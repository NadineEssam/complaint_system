@extends('dashboard.layouts.app')

@section('title', 'Dashboard')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  /* ================= ROOT ================= */
  :root {
    --primary: #0d6efd;
    --primary-light: #eaf2ff;
    --success: #16a34a;
    --warning: #d97706;
    --danger: #dc2626;
    --dark: #1f2937;
    --gray: #6b7280;
    --border: #eef0f3;
    --card: #ffffff;
    --bg: #f7f8fa;
  }

  .section.dashboard, .section.dashboard * {
    font-family: 'Cairo', 'Tahoma', sans-serif;
  }

  /* ================= FORM ================= */

  .form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray);
  }

  .form-control-lg {
    border-radius: 10px;
    border: 1px solid var(--border);
  }

  .form-control-lg:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .12);
  }

  /* ================= GLOBAL ================= */
  .section.dashboard {
    background: var(--bg);
    padding: 24px;
    border-radius: 16px;
  }

  .row.kpi-row {
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    padding-bottom: 4px;
  }

  .row.kpi-row>.col {
    flex: 1 1 0 !important;
    min-width: 150px;
  }

  @media(max-width:992px) {
    .row.kpi-row>.col {
      min-width: 170px;
    }
  }

  /* ================= PAGE TITLE ================= */
  .pagetitle h1 {
    font-size: 24px;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 6px;
  }

  .breadcrumb {
    margin-bottom: 0;
  }

  .breadcrumb-item,
  .breadcrumb-item a {
    color: #98a2b3;
    font-size: 13px;
    text-decoration: none;
  }

  /* ================= CARDS ================= */
  .card {
    border: 1px solid var(--border) !important;
    border-radius: 14px !important;
    overflow: hidden;
    background: var(--card);
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
  }

  .card-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--gray);
    margin-bottom: 18px;
  }

  /* ================= KPI CARDS ================= */
  .kpi-card {
    position: relative;
    padding: 4px;
  }

  .kpi-card .card-body {
    padding: 18px;
  }

  .card-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
  }

  .bg-primary-gradient { background: #eaf2ff; color: #3b76e0; }
  .bg-success-gradient { background: #eafaf0; color: #2f9e63; }
  .bg-warning-gradient { background: #fdf3e6; color: #c3791f; }
  .bg-danger-gradient  { background: #fdecee; color: #d3556a; }
  .bg-info-gradient    { background: #eef0fc; color: #6469c9; }

  .counter {
    font-size: 24px;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 0;
  }

  .counter-label {
    color: #9ca3af;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
  }

  /* ================= HEADERS ================= */
  .custom-header {
    padding: 16px 20px;
    font-size: 13px;
    font-weight: 700;
    border-bottom: 1px solid var(--border);
    background: #fff;
    color: var(--dark);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .custom-header i {
    font-size: 17px;
    opacity: .8;
    color: var(--gray);
  }

  .section.dashboard h4 {
    font-size: 13px;
    font-weight: 700;
    color: var(--dark);
  }

  .section.dashboard h4 + p,
  .section.dashboard p.small {
    font-size: 13px;
    color: var(--gray);
  }

  /* ================= CHART WRAPPER ================= */
  .chart-box {
    padding: 10px;
  }

  /* ================= GOVERNORATE ================= */
  .gov-header {
    background: var(--success);
    color: #fff;
  }

  /* ================= DARK MODE ================= */
  .dark-mode {
    background: #111827;
    color: #fff;
  }

  .dark-mode .card {
    background: #1f2937;
  }

  .dark-mode .card-title,
  .dark-mode .counter,
  .dark-mode .custom-header {
    color: #fff;
  }

  /* ================= RESPONSIVE ================= */
  @media(max-width:768px) {

    .section.dashboard {
      padding: 15px;
    }

    .pagetitle {
      flex-direction: column;
      align-items: flex-start !important;
      gap: 10px;
    }

    .counter {
      font-size: 22px;
    }

    .card-icon {
      width: 50px;
      height: 50px;
      font-size: 20px;
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

<div class="card mb-4">
  <div class="card-body">

    <form method="GET" class="row g-3 align-items-end">

      {{-- FROM --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">من تاريخ</label>
       <input type="text"
   dir="rtl"
   lang="ar"
   name="from"
   class="form-control form-control-lg"
   value="{{ request('from') }}"
   readonly>
      </div>

      {{-- TO --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">إلى تاريخ</label>
        <input type="text"
   dir="rtl"
   lang="ar"
   name="to"
   class="form-control form-control-lg"
   value="{{ request('to') }}"
   readonly>
      </div>

      {{-- BUTTONS --}}
      <div class="col-md-4 d-flex gap-2">

        <button class="btn btn-primary btn-lg w-100">
          <i class="bi bi-funnel"></i>
          فلترة
        </button>

        <a href="{{ route('home') }}"
           class="btn btn-outline-secondary btn-lg w-100">
          إعادة ضبط
        </a>

      </div>

    </form>

  </div>
</div>

  {{-- ================= KPI ================= --}}
  <div class="row g-4 mb-4 kpi-row">

    {{-- TOTAL --}}
    <div class="col">
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
    <div class="col">
      <div class="card kpi-card">
        <div class="card-body">

          <div class="d-flex align-items-center justify-content-between">

            <div>
              <div class="card-title mb-2">تم الحل</div>

              <h2 class="counter">{{ $statusSolved }}</h2>

              <div class="counter-label">الشكاوى المكتملة</div>
            </div>

            <div class="card-icon bg-success-gradient">
              <i class="bi bi-check-circle"></i>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="card kpi-card">
        <div class="card-body">

          <div class="d-flex align-items-center justify-content-between">

            <div>
              <div class="card-title mb-2">قيد المعالجة</div>

              <h2 class="counter">{{ $statusProcessing }}</h2>

              <div class="counter-label">تحتاج متابعة</div>
            </div>

            <div class="card-icon bg-warning-gradient">
              <i class="bi bi-hourglass-split"></i>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="card kpi-card">
        <div class="card-body">

          <div class="d-flex align-items-center justify-content-between">

            <div>
              <div class="card-title mb-2">جديدة</div>

              <h2 class="counter"> {{ $statusNew }} </h2>

              <div class="counter-label">شكاوى جديدة</div>
            </div>

            <div class="card-icon bg-primary-gradient">
              <i class="bi bi-plus-circle"></i>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="card kpi-card">
        <div class="card-body">

          <div class="d-flex align-items-center justify-content-between">

            <div>
              <div class="card-title mb-2">تم الحفظ</div>

              <h2 class="counter">{{ $statusSaved }}</h2>

              <div class="counter-label">مسودات محفوظة</div>
            </div>

            <div class="card-icon bg-danger-gradient">
              <i class="bi bi-save"></i>
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
  <!-- <div class="row mt-4">

    <div class="col-12">

      <div class="card">

        <div class="custom-header">
          <span>🏢 الشكاوى حسب الإدارات</span>
          <i class="bi bi-buildings"></i>
        </div>

        <div class="card-body">
          <div style="overflow-x:auto;">
            <div id="departmentChart" style="min-width:1200px;height:500px;"></div>
          </div>
        </div>

      </div>

    </div>

  </div> -->
  <div class="row mt-2">
    <div class="card mb-12">

      <div class="custom-header">
        <span>📡 مصادر الشكاوى</span>
        <i class="bi bi-broadcast"></i>
      </div>

      <div class="card-body chart-box">
        <div id="sourceChart"></div>
      </div>

    </div>
  </div> 

  <div class="row mt-4">
  <div class="col-12">
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="mb-1 fw-bold">🏛️ الشكاوى حسب القطاعات</h4>
            <p class="text-muted mb-0 small">توزيع عدد الشكاوى على القطاعات</p>
          </div>
          <div class="rounded-circle d-flex align-items-center justify-content-center"
            style="width:55px;height:55px;background:#eaf2ff;color:#0d6efd;font-size:22px;">
            <i class="bi bi-diagram-3"></i>
          </div>
        </div>

        <div id="sectorChart"></div>

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
                  🗺️ الشكاوى حسب الفروع
                </h4>

                <p class="text-muted mb-0 small">
                  توزيع عدد الشكاوى على الفروع
                </p>
              </div>

              <div class="rounded-circle d-flex align-items-center justify-content-center"
                style="
                 width:55px;
                 height:55px;
                 background:#eaf2ff;
                 color:#0d6efd;
                 font-size:22px;
               ">
                <i class="bi bi-map"></i>
              </div>

            </div>

            <div id="offChart"></div>

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

        if (start >= end) {
          el.innerText = end;
          clearInterval(interval);
        } else {
          el.innerText = start;
        }

      }, 20);

    });

    // ================= DATA =================
    const typeLabels = @json($requestTypesStats -> pluck('requesttypename'));
    const typeData = @json($requestTypesStats -> pluck('complaints_count'));

    const statusLabels = @json($statusStats -> map(fn($s) => $s -> status -> statusText ?? 'غير معروف') -> values());
    const statusData = @json($statusStats -> pluck('total'));

    const sectorLabels = @json(collect($sectorStats)->pluck('name'));
    const sectorData   = @json(collect($sectorStats)->pluck('total'));

    const officeLabels = @json(collect($officeStats) -> pluck('name'));
    const officeData = @json(collect($officeStats) -> pluck('total'));

    const sourceLabels = @json($sourceStats -> pluck('comsourcesname'));
    const sourceData = @json($sourceStats -> pluck('total'));

    const closeData = @json($closeReasonStats);

    // ================= REPORTS CHART =================
    new ApexCharts(document.querySelector("#reportsChart"), {

      series: [{
        name: 'عدد الشكاوى',
        data: typeData
      }],

      chart: {
        type: 'bar',
        fontFamily: "Cairo, sans-serif",
        height: 360,
        toolbar: {
          show: false
        }
      },

      colors: ['#7ba3e8'],

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
        fontFamily: "Cairo, sans-serif",
        toolbar: {
          show: false
        }
      },

      colors: ['#5fb88a'],

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
        height: 340,
        fontFamily: "Cairo, sans-serif",
      },

      labels: typeLabels,

      colors: [
        '#7ba3e8',
        '#5fb88a',
        '#d6a85e',
        '#d3849a',
        '#9b8fd9',
        '#6fb8c9'
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
                formatter: function(w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                }
              }
            }
          }
        }
      }

    }).render();

    // ================= DEPARTMENT =================
    new ApexCharts(document.querySelector("#sourceChart"), {

      series: sourceData,

      chart: {
        type: 'donut',
        height: 340,

        fontFamily: "Cairo, sans-serif",
      },

      labels: sourceLabels,

      colors: [
        '#6fb8a8',
        '#7ba3e8',
        '#d6a85e',
        '#d3849a',
        '#9b8fd9',
        '#6fb8c9'
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
                formatter: function(w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                }
              }
            }
          }
        }
      }

    }).render();

new ApexCharts(document.querySelector("#sectorChart"), {
  series: [{ name: "عدد الشكاوى", data: sectorData }],

  chart: {
    type: "bar",
    height: 620,
    background: "transparent",
    toolbar: { show: false },
    fontFamily: "Cairo, sans-serif",
  },

  colors: ["#6fb8a8"],

  theme: { mode: "light" },

  grid: {
    borderColor: "#e5e7eb",
    strokeDashArray: 4,
    xaxis: { lines: { show: true } },
    yaxis: { lines: { show: false } },
    padding: {
      left: 100,
      right: 40,
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
      dataLabels: { position: "top" },
    },
  },

  dataLabels: {
    enabled: true,
    offsetX: 50,
    style: {
      fontSize: "13px",
      fontWeight: "700",
      colors: ["#111827"],
    },
    formatter: function(val) { return val; },
  },

  stroke: {
    show: true,
    width: 1,
    colors: ["#ffffff"],
  },

  xaxis: {
    categories: sectorLabels,
    axisBorder: { show: false },
    axisTicks: { show: false },
    labels: {
      style: {
        colors: "#6b7280",
        fontSize: "12px",
        fontWeight: 500,
      },
    },
  },

  // ✅ Only ONE yaxis here
  yaxis: {
    labels: {
      maxWidth: 400,
      offsetX: -300,
      style: {
        fontSize: "14px",
        fontWeight: 600,
        colors: "#0f172a",
      },
    },
  },

  tooltip: {
    theme: "light",
    style: { fontSize: "13px", fontFamily: "Cairo, sans-serif" },
  },

  legend: { show: false },

  states: {
    hover: {
      filter: { type: "lighten", value: 0.08 },
    },
  },

  responsive: [{
    breakpoint: 768,
    options: {
      chart: { height: 420 },
      yaxis: {
        labels: { style: { fontSize: "12px" } },
      },
    },
  }],

}).render();
    // ================= GOVERNORATE =================
    new ApexCharts(document.querySelector("#offChart"), {
      series: [{
        name: "عدد الشكاوى",
        data: officeData,
      }, ],

      chart: {
        type: "bar",
        height: 520,
        background: "transparent",
        toolbar: {
          show: false,
        },
        fontFamily: "Cairo, sans-serif",
      },

      colors: ["#6fb8a8"],

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
        formatter: function(val) {
          return val;
        },
      },

      stroke: {
        show: true,
        width: 1,
        colors: ["#ffffff"],
      },

      xaxis: {
        categories: officeLabels,

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

          offsetX: 140,
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

      responsive: [{
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
      }, ],
    }).render();

    // ================= CLOSE REASON =================
    new ApexCharts(document.querySelector("#closeReasonChart"), {

      series: closeData.map(i => i.total),

      chart: {
        type: 'donut',
        height: 340,
        
        fontFamily: "Cairo, sans-serif",
      },

      labels: closeData.map(i => i.name),

      colors: [
        '#5fb88a',
        '#7ba3e8',
        '#d6a85e',
        '#d3849a',
        '#9b8fd9'
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
                formatter: function(w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                }
              }
            }
          }
        }
      }

    }).render();

  });

  flatpickr("input[name='from']", {
    locale: "ar",
    dateFormat: "Y-m-d",
    maxDate: "today"
});

flatpickr("input[name='to']", {
    locale: "ar",
    dateFormat: "Y-m-d",
    maxDate: "today"
});
</script>

@endpush