<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
  <style>
    /* ════════════════════════════════════════════════════════════════ */
    /* PAGE LOADER - Global route navigation loader */
    /* ════════════════════════════════════════════════════════════════ */
    #page-loader {
      position: fixed;
      inset: 0;
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(8px);
      z-index: 999999;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.4s ease;
      opacity: 1;
      visibility: visible;
      font-family: 'Cairo', 'Tahoma', sans-serif;
    }

    #page-loader.hidden {
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
    }

    .loader-content {
      text-align: center;
    }

    .loader-logo {
      width: 85px;
      height: 85px;
      margin: auto;
      border-radius: 24px;
      background: linear-gradient(135deg, #003b8e, #0057d9);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 34px;
      margin-bottom: 25px;
      animation: float 2s ease-in-out infinite;
      box-shadow: 0 10px 30px rgba(0, 59, 142, 0.25);
    }

    .modern-spinner {
      width: 50px;
      height: 50px;
      margin: auto;
      border: 4px solid #dbeafe;
      border-top: 4px solid #003b8e;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }

    .loading-text {
      margin-top: 20px;
      font-size: 16px;
      color: #003b8e;
      font-weight: 600;
      letter-spacing: 0.5px;
      font-family: 'Cairo', 'Tahoma', sans-serif;
    }

    .dots::after {
      content: '';
      animation: dots 1.5s infinite;
    }

    @keyframes spin {
      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes float {
      0%,
      100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    @keyframes dots {
      0% {
        content: '';
      }
      33% {
        content: '.';
      }
      66% {
        content: '..';
      }
      100% {
        content: '...';
      }
    }
  </style>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title', 'Dashboard')</title>
  <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
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

  <!-- Cairo Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!--  -->
  
  @stack('headScripts')
</head>

<body>

  <div id="page-loader">
    <div class="loader-content">
      <!-- <div class="loader-logo">
        <i class="fa-solid fa-chart-line"></i>
      </div> -->

      <div class="modern-spinner"></div>

      <p class="loading-text">
        جاري التحميل<span class="dots"></span>
      </p>
    </div>
  </div>

  @include('dashboard.partials.header')

  @include('dashboard.partials.sidebar')

  <main id="main" class="main">
    @yield('content')
  </main>

  @include('dashboard.partials.footer')

  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
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
      font-family: 'Cairo', 'Tahoma', sans-serif;
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

  <!-- ════════════════════════════════════════════════════════════════ -->
  <!-- GLOBAL ROUTE LOADER SCRIPT -->
  <!-- ════════════════════════════════════════════════════════════════ -->
  <script>
    /**
     * Global Page Loader for Route Navigation
     * Shows loader when clicking links or submitting forms
     * Hides when new page loads
     */

    const pageLoader = {
      element: document.getElementById('page-loader'),
      loaderTimeout: null,

      show: function() {
        if (this.element) {
          this.element.classList.remove('hidden');
          clearTimeout(this.loaderTimeout);
          
          // Safety timeout - hide after 5 seconds
          this.loaderTimeout = setTimeout(() => {
            this.hide();
          }, 5000);
        }
      },

      hide: function() {
        if (this.element) {
          this.element.classList.add('hidden');
          clearTimeout(this.loaderTimeout);
        }
      }
    };

    // ── Show loader on link click ──
    document.addEventListener('click', function(e) {
  const link = e.target.closest('a');
  const rawHref = link?.getAttribute('href') || '';

  if (!link ||
    link.hasAttribute('data-no-loader') ||
    link.target === '_blank' ||
    rawHref.startsWith('#') ||                 // catches "#step-1", "#step-2", "#anything"
    rawHref.startsWith('javascript') ||
    link.hasAttribute('data-bs-toggle') ||
    link.hasAttribute('download')) {
    return;
  }


      // Check if it's same domain
      try {
        const linkUrl = new URL(link.href);
        const currentUrl = new URL(window.location.href);

        if (linkUrl.origin === currentUrl.origin) {
          pageLoader.show();
        }
      } catch (e) {
        // If URL parsing fails, still show loader for relative links
        if (link.href.startsWith('/') || !link.href.includes('://')) {
          pageLoader.show();
        }
      }
    });

    // ── Show loader on form submit ──
    document.addEventListener('submit', function(e) {
      const form = e.target;

      // Ignore if form has data-no-loader attribute
      if (form.hasAttribute('data-no-loader')) {
        return;
      }

      // Only show loader if form posts to same domain
      if (form.method.toUpperCase() === 'POST' || form.method.toUpperCase() === 'GET') {
        const action = form.getAttribute('action') || window.location.href;

        try {
          const formUrl = new URL(action, window.location.href);
          const currentUrl = new URL(window.location.href);

          if (formUrl.origin === currentUrl.origin) {
            pageLoader.show();
          }
        } catch (e) {
          pageLoader.show();
        }
      }
    });

    // ── Hide loader when page loads ──
    window.addEventListener('load', function() {
      // Add a small delay for smoother transition
      setTimeout(() => {
        pageLoader.hide();
      }, 300);
    });

    // ── Hide loader when user navigates back/forward ──
    window.addEventListener('pageshow', function() {
      pageLoader.hide();
    });

    // ── Initial page load - hide loader after 500ms ──
    // window.addEventListener('DOMContentLoaded', function() {
    //   setTimeout(() => {
    //     pageLoader.hide();
    //   }, 500);
    // });

    // ── Hide loader on jQuery document ready ──
    $(document).ready(function() {
      setTimeout(() => {
        pageLoader.hide();
      }, 500);
    });

    // Expose globally for manual control if needed
    window.pageLoader = pageLoader;
  </script>
</body>

</html>