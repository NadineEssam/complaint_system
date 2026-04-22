<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login</title>

  <!-- Laravel asset helper -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">

  <!-- CSS -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

<main>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              <a href="/" class="logo d-flex align-items-center w-auto">
                <img src="{{ asset('logo.png') }}" style="width: 150px" alt="MSMEDA Logo">
                <br>
                <span class="d-none d-lg-block">نظام الشكاوي</span>
              </a>
            </div>

            <div class="card mb-3">
              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">تسجيل الدخول</h5>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                  <div style="direction: rtl" class="alert alert-danger">
                    {{ $errors->first() }}
                  </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="row g-3">
                  @csrf

                  {{-- Username  --}}
                  <div class="col-12">
                    <label class="form-label">Username</label>
                    <input type="text"
                           name="userID"
                           class="form-control"
                           value="{{ old('userID') }}"
                           required>
                  </div>

                  {{-- Password --}}
                  <div class="col-12">
                    <label class="form-label">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           required>
                  </div>

                  {{-- Remember --}}
                  {{-- <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input"
                             type="checkbox"
                             name="remember"
                             id="rememberMe">
                      <label class="form-check-label" for="rememberMe">
                        Remember me
                      </label>
                    </div>
                  </div> --}}

                  {{-- Submit --}}
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">
                      تسجيل الدخول
                    </button>
                  </div>

                </form>

              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

  </div>
</main>

<!-- JS -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>