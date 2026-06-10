<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
  <style>
#page-loader{
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(8px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .4s ease;
}

.loader-content{
    text-align: center;
}

.loader-logo{
    width: 85px;
    height: 85px;
    margin: auto;
    border-radius: 24px;
    background: linear-gradient(135deg,#003b8e,#0057d9);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 34px;
    margin-bottom: 25px;
    animation: float 2s ease-in-out infinite;
    box-shadow: 0 10px 30px rgba(0,59,142,.25);
}

.modern-spinner{
    width: 50px;
    height: 50px;
    margin: auto;
    border: 4px solid #dbeafe;
    border-top: 4px solid #003b8e;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}

.loading-text{
    margin-top: 20px;
    font-size: 16px;
    color: #003b8e;
    font-weight: 600;
    letter-spacing: .5px;
}

.dots::after{
    content:'';
    animation:dots 1.5s infinite;
}

@keyframes spin{
    100%{
        transform: rotate(360deg);
    }
}

@keyframes float{
    0%,100%{
        transform: translateY(0px);
    }
    50%{
        transform: translateY(-10px);
    }
}

@keyframes dots{
    0%{
        content:'';
    }
    33%{
        content:'.';
    }
    66%{
        content:'..';
    }
    100%{
        content:'...';
    }
}
</style>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title', 'Dashboard')</title>

  <!-- CSS -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <!-- RTL Support -->
  <link href="{{ asset('assets/css/rtl.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
  @stack('headScripts')
</head>

<body>
 <div id="page-loader">
    <div class="loader-content">

        <div class="loader-logo">
            <i class="fa-solid fa-chart-line"></i>
        </div>

        <div class="modern-spinner"></div>

        <p class="loading-text">
            Loading Dashboard<span class="dots"></span>
        </p>

    </div>
</div>

  {{-- Header --}}
  @include('dashboard.partials.header')

  {{-- Sidebar --}}
  @include('dashboard.partials.sidebar')

  <main id="main" class="main">
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('dashboard.partials.footer')

  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

  <!-- JS -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <script src="{{ asset('assets/js/main.js') }}"></script>

  @stack('footerScripts')



  @include('sweetalert::alert')
  <style>
    .swal2-popup {
      border-radius: 20px !important;
      padding: 25px !important;
    }

    .swal2-title {
      font-size: 22px !important;
      font-weight: bold;
    }

    .swal2-confirm {
      background: linear-gradient(45deg, #4CAF50, #2ecc71) !important;
      border-radius: 8px !important;
    }

    .swal2-cancel {
      background: #e74c3c !important;
    }
  </style>
  <script>
window.addEventListener("load", function () {

    const loader = document.getElementById("page-loader");

    setTimeout(() => {
        loader.style.opacity = "0";
        loader.style.visibility = "hidden";
    }, 500);

});
</script>
</body>

</html>