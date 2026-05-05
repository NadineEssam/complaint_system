@extends('dashboard.layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
/* ================= GLOBAL ================= */
.section.dashboard{
  background: #f6f8fb;
  padding: 20px;
  border-radius: 12px;
}

/* ================= KPI CARDS ================= */
.kpi-card, .card.info-card{
  border: none;
  border-radius: 18px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 6px 20px rgba(0,0,0,0.06);
  transition: all 0.3s ease;
}

.kpi-card:hover, .card.info-card:hover{
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.card-title{
  font-weight: 600;
  font-size: 15px;
  color: #555;
}

/* ICON BOX */
.card-icon{
  width: 60px;
  height: 60px;
  border-radius: 14px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size: 24px;
}

/* GRADIENT ICONS */
.bg-primary{
  background: linear-gradient(135deg,#4facfe,#00f2fe) !important;
}
.bg-success{
  background: linear-gradient(135deg,#43e97b,#38f9d7) !important;
}
.bg-warning{
  background: linear-gradient(135deg,#fddb92,#d1fdff) !important;
  color:#333 !important;
}
.bg-danger{
  background: linear-gradient(135deg,#fa709a,#fee140) !important;
}

/* COUNTER */
.counter{
  font-size: 26px;
  font-weight: 700;
  color: #222;
}

/* ================= CARDS ================= */
.card{
  border-radius: 18px !important;
  border: none !important;
  box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

/* HEADER */
.pagetitle h1{
  font-size: 24px;
  font-weight: 700;
}

/* ================= DARK MODE ================= */
.dark-mode{
  background:#121212;
  color:#fff;
}
.dark-mode .card,
.dark-mode .card.info-card{
  background:#1e1e1e;
  color:#fff;
}
.dark-mode .card-title{
  color:#ddd;
}.close-card{
  border-radius: 18px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0,0,0,0.06);
  transition: 0.3s ease;
}

.close-card:hover{
  transform: translateY(-5px);
  box-shadow: 0 18px 40px rgba(0,0,0,0.12);
}

.close-card .card-header{
  background: linear-gradient(135deg,#43e97b,#38f9d7);
  color: #fff;
  font-weight: 700;
  font-size: 15px;
  border-bottom: none;
  padding: 14px 18px;
}

.close-card .card-header i{
  font-size: 18px;
  opacity: 0.9;
}
</style>

<section class="section dashboard">

{{-- ================= HEADER ================= --}}
<div class="pagetitle mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1>إحصائيات الشكاوى</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div>

</div>

{{-- ================= KPI CARDS ================= --}}


 <div class="row g-4 mb-4">

  <!-- Total -->
  <div class="col-xxl-3 col-md-6">
    <div class="card info-card kpi-card">
      <div class="card-body">
        <h5 class="card-title">إجمالي الشكاوى <span>| لليوم</span></h5>

        <div class="d-flex align-items-center">
          <div class="card-icon bg-primary text-white">
            <i class="bi bi-collection"></i>
          </div>
          <div class="ps-3">
            <h6 class="counter">{{ $total }}</h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Solved -->
  <div class="col-xxl-3 col-md-6">
    <div class="card info-card kpi-card">
      <div class="card-body">
        <h5 class="card-title">تم الحل <span>| لليوم</span></h5>

        <div class="d-flex align-items-center">
          <div class="card-icon bg-success text-white">
            <i class="bi bi-check-circle"></i>
          </div>
          <div class="ps-3">
            <h6 class="counter">{{ $status24Total }}</h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pending -->
  <div class="col-xxl-3 col-md-6">
    <div class="card info-card kpi-card">
      <div class="card-body">
        <h5 class="card-title">قيد المعالجة <span>| لليوم</span></h5>

        <div class="d-flex align-items-center">
          <div class="card-icon bg-warning text-dark">
            <i class="bi bi-hourglass-split"></i>
          </div>
          <div class="ps-3">
            <h6 class="counter">{{ $total - $status24Total }}</h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Gov -->
  <div class="col-xxl-3 col-md-6">
    <div class="card info-card kpi-card">
      <div class="card-body">
        <h5 class="card-title">المحافظات</h5>

        <div class="d-flex align-items-center">
          <div class="card-icon bg-danger text-white">
            <i class="bi bi-geo-alt"></i>
          </div>
          <div class="ps-3">
            <h6 class="counter">{{ $govStats->count() }}</h6>
          </div>
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

    <div class="card close-card border-0 mb-4">
  
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>📊 الشكاوى حسب النوع</span>
    <i class="bi bi-bar-chart-fill"></i>
  </div>

  <div class="card-body">
    <div id="reportsChart"></div>
  </div>

</div>

    <div class="card shadow border-0">
      <div class="card-body">
        <h5 class="card-title">📈 الشكاوى حسب الحالة</h5>
        <div id="statusChart"></div>
      </div>
    </div>

  </div>

  {{-- RIGHT --}}
  <div class="col-lg-4">

    <div class="card shadow border-0 mb-4">
      <div class="card-header bg-primary text-white">
        توزيع الشكاوى
      </div>
      <div class="card-body">
        <div id="trafficChart"></div>
      </div>
    </div>
<div class="card close-card border-0">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>أسباب الإغلاق</span>
    <i class="bi bi-pie-chart-fill"></i>
  </div>

  <div class="card-body">
    <div id="closeReasonChart"></div>
  </div>
</div>

  </div>

</div>

{{-- ================= DEPARTMENTS ================= --}}
<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow border-0">
      <div class="card-body">
        <h5 class="card-title">🏢 الشكاوى حسب الإدارات</h5>
        <div id="departmentChart" style="height: 450px;"></div>
      </div>
    </div>
  </div>
</div>

{{-- ================= GOVERNORATES ================= --}}
<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow border-0">
      <div class="card-header bg-success text-white">
        🗺️ الشكاوى حسب المحافظات
      </div>
      <div class="card-body">
        <div id="govChart" style="height: 400px;"></div>
      </div>
    </div>
  </div>
</div>

</section>

@endsection

@push('footerScripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

  // ================= COUNTERS =================
  document.querySelectorAll('.counter').forEach(el => {
    let end = parseInt(el.innerText);
    let start = 0;
    let step = Math.ceil(end / 40);

    let interval = setInterval(() => {
      start += step;
      if(start >= end){
        el.innerText = end;
        clearInterval(interval);
      } else {
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

  // ================= CHARTS =================

 new ApexCharts(document.querySelector("#reportsChart"), {
  series: [{ name: 'عدد الشكاوى', data: typeData }],

  chart: { 
    type: 'bar', 
    height: 350 
  },

  colors: ['#4facfe'],

  plotOptions: {
    bar: {
      columnWidth: '25%' // 👈 thinner bars
    }
  },

  xaxis: { categories: typeLabels }

}).render();

  new ApexCharts(document.querySelector("#statusChart"), {
    series: [{ name: 'الحالات', data: statusData }],
    chart: { type: 'area', height: 350 },
    stroke: { curve: 'smooth' },
    xaxis: { categories: statusLabels }
  }).render();

 new ApexCharts(document.querySelector("#trafficChart"), {
  series: typeData,

  chart: {
    type: 'donut',
    height: 340,
    animations: {
      enabled: true,
      easing: 'easeinout',
      speed: 800
    }
  },

  labels: typeLabels,

  colors: [
    '#43e97b', '#38f9d7', '#4facfe', '#00f2fe',
    '#f093fb', '#f5576c', '#fa709a', '#fee140'
  ],

  plotOptions: {
    pie: {
      donut: {
        size: '72%',
        labels: {
          show: true,
          name: {
            fontSize: '14px',
            fontWeight: 600,
            color: '#444'
          },
          value: {
            fontSize: '18px',
            fontWeight: 700,
            color: '#111'
          },
          total: {
            show: true,
            label: 'الإجمالي',
            fontSize: '14px',
            fontWeight: 600,
            formatter: function (w) {
              return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
            }
          }
        }
      }
    }
  },

  stroke: {
    width: 3
  },

  legend: {
    position: 'bottom',
    fontSize: '13px',
    fontWeight: 600,
    markers: {
      radius: 12
    }
  },

  tooltip: {
    y: {
      formatter: val => val + " شكوى"
    }
  }

}).render();

  new ApexCharts(document.querySelector("#departmentChart"), {
    series: [{ name: 'عدد الشكاوى', data: deptCounts }],
    chart: { type: 'area', height: 450 },
    stroke: { curve: 'smooth', width: 3 },
    fill: { type: 'gradient' },
    dataLabels: { enabled: true },
    xaxis: { categories: deptLabels }
  }).render();
new ApexCharts(document.querySelector("#govChart"), {
  series: [{
    name: 'عدد الشكاوى',
    data: govData
  }],

  chart: {
    type: 'bar',
    height: 500,
    width: '100%',
    toolbar: { show: false },
    fontFamily: 'Arial, sans-serif'
  },

  colors: ['#43e97b'], // نفس لون الهيدر

  plotOptions: {
    bar: {
      horizontal: true,
      borderRadius: 8,
      barHeight: '70%',
      distributed: false
    }
  },

  fill: {
    type: 'gradient',
    gradient: {
      shade: 'light',
      type: "horizontal",
      gradientToColors: ['#38f9d7'],
      opacityFrom: 0.95,
      opacityTo: 0.85,
      stops: [0, 100]
    }
  },

  dataLabels: {
    enabled: true,
    style: {
      fontSize: '12px',
      fontWeight: 700,
      colors: ['#111']
    }
  },

  xaxis: {
    categories: govLabels,
    axisBorder: { show: false },
    axisTicks: { show: false },
    labels: {
      style: {
        fontSize: '13px',
        fontWeight: 600,
        colors: '#666'
      }
    }
  },

  yaxis: {
    labels: {
      align: 'left',
      offsetX: 60,
      style: {
        fontSize: '15px',
        fontWeight: 600,
        colors: '#222'
      }
    }
  },

  grid: {
    borderColor: '#eaeaea',
    strokeDashArray: 5
  },

  tooltip: {
    theme: 'light',
    y: {
      formatter: val => val + " شكوى"
    }
  }

}).render();

new ApexCharts(document.querySelector("#closeReasonChart"), {
  series: closeData.map(i => i.total),
  
  chart: {
    type: 'donut',
    height: 340,
    animations: {
      enabled: true,
      easing: 'easeinout',
      speed: 900,
      animateGradually: {
        enabled: true,
        delay: 150
      },
      dynamicAnimation: {
        enabled: true,
        speed: 500
      }
    }
  },

  labels: closeData.map(i => i.name),

  colors: [
    '#43e97b', '#38f9d7', '#4facfe', '#00f2fe',
    '#f093fb', '#f5576c', '#fa709a', '#fee140'
  ],

  plotOptions: {
    pie: {
      donut: {
        size: '72%',
        labels: {
          show: true,
          name: {
            show: true,
            fontSize: '14px',
            fontWeight: 600,
            color: '#444'
          },
          value: {
            show: true,
            fontSize: '18px',
            fontWeight: 700,
            color: '#111'
          },
          total: {
            show: true,
            label: 'الإجمالي',
            fontSize: '14px',
            fontWeight: 600,
            color: '#666',
            formatter: function (w) {
              return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
            }
          }
        }
      }
    }
  },

  stroke: {
    width: 3
  },

  dataLabels: {
    enabled: true,
    style: {
      fontSize: '12px',
      fontWeight: 600
    }
  },

  legend: {
    position: 'bottom',
    fontSize: '13px',
    fontWeight: 600,
    markers: {
      radius: 12
    }
  },

  tooltip: {
    y: {
      formatter: val => val + " شكوى"
    }
  }

}).render();
 
});
</script>
@endpush