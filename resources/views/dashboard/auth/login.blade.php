<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>تسجيل الدخول - نظام خدمة العملاء</title>

  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <style>
    * { box-sizing: border-box; }

    body {
      font-family: 'Cairo', sans-serif;
      background: #f0f4f9;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 20px;
    }

    .login-wrapper {
      width: 100%;
      max-width: 440px;
    }

    /* ── Logo card ── */
    .logo-card {
      background: #fff;
      border-radius: 20px 20px 0 0;
      padding: 32px 40px 24px;
      text-align: center;
      border-bottom: 2px solid #f0f4f9;
      box-shadow: 0 4px 24px rgba(0,59,142,0.07);
    }

    .logo-card img {
      width: 240px;
      max-width: 100%;
      height: auto;
    }

    .system-title {
      font-size: 18px;
      font-weight: 700;
      color: #003b8e;
      margin: 14px 0 0;
      letter-spacing: 0.3px;
    }

    /* ── Form card ── */
    .form-card {
      background: #fff;
      border-radius: 0 0 20px 20px;
      padding: 28px 40px 36px;
      box-shadow: 0 8px 32px rgba(0,59,142,0.10);
    }

    .form-card h5 {
      font-size: 15px;
      font-weight: 600;
      color: #6b7280;
      text-align: center;
      margin-bottom: 24px;
    }

    /* ── Input groups ── */
    .field-group {
      margin-bottom: 18px;
    }

    .field-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 7px;
    }

    .input-with-icon {
      position: relative;
    }

    .input-with-icon .icon {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 16px;
      pointer-events: none;
    }

    .input-with-icon input {
      width: 100%;
      padding: 12px 42px 12px 16px;
      border: 1.5px solid #e5e7eb;
      border-radius: 12px;
      font-family: 'Cairo', sans-serif;
      font-size: 14px;
      color: #111827;
      background: #f9fafb;
      transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
      outline: none;
      direction: ltr;
      text-align: right;
    }

    .input-with-icon input:focus {
      border-color: #003b8e;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(0,59,142,0.10);
    }

    /* ── Alert ── */
    .alert-danger {
      background: #fff0f0;
      border: 1px solid #fecaca;
      color: #dc2626;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 13px;
      margin-bottom: 18px;
      text-align: right;
    }

    /* ── Submit button ── */
    .btn-login {
      width: 100%;
      padding: 13px;
      background: linear-gradient(135deg, #003b8e 0%, #0056cc 100%);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-family: 'Cairo', sans-serif;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      margin-top: 6px;
      transition: opacity 0.2s, transform 0.1s;
      letter-spacing: 0.5px;
    }

    .btn-login:hover { opacity: 0.92; }
    .btn-login:active { transform: scale(0.99); }

    /* ── Footer ── */
    .login-footer {
      text-align: center;
      margin-top: 18px;
      font-size: 12px;
      color: #9ca3af;
    }
  </style>
</head>

<body>
  <div class="login-wrapper">

    {{-- ── Logo ── --}}
    <div class="logo-card">
      <img src="{{ asset('msmeda_logo_web.png') }}" alt="MSMEDA Logo">
      <p class="system-title">نظام خدمة العملاء</p>
    </div>

    {{-- ── Form ── --}}
    <div class="form-card">
      <h5>أدخل بيانات الدخول للمتابعة</h5>

      {{-- Errors --}}
      @if ($errors->any())
        <div class="alert-danger">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Username --}}
        <div class="field-group">
          <label for="userID">اسم المستخدم</label>
          <div class="input-with-icon">
            <i class="bi bi-person icon"></i>
            <input type="text"
                   id="userID"
                   name="userID"
                   value="{{ old('userID') }}"
                   placeholder="أدخل اسم المستخدم"
                   required
                   autocomplete="username">
          </div>
        </div>

        {{-- Password --}}
        <div class="field-group">
          <label for="password">كلمة المرور</label>
          <div class="input-with-icon">
            <i class="bi bi-lock icon"></i>
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="أدخل كلمة المرور"
                   required
                   autocomplete="current-password">
          </div>
        </div>

        <button type="submit" class="btn-login">تسجيل الدخول</button>
      </form>
    </div>

    <p class="login-footer">جهاز تنمية المشروعات المتوسطة والصغيرة ومتناهية الصغر &copy; {{ date('Y') }}</p>
  </div>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>