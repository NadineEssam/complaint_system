@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

        <div class="d-flex justify-content-center py-4">
          <a href="{{ route('home') }}" class="logo d-flex align-items-center w-auto">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
            <span class="d-none d-lg-block">Complaint System</span>
          </a>
        </div>

        <div class="card mb-3">
          <div class="card-body">
            <div class="pt-4 pb-2">
              <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
              <p class="text-center small">Enter your username & password to login</p>
            </div>

            <form method="POST" action="{{ route('login.submit') }}" class="row g-3">
              @csrf

              @if(session('error'))
              <div class="alert alert-danger">
                {{ session('error') }}
              </div>
              @endif

              <div class="col-12">
                <label class="form-label">User ID</label>
                <input type="text" name="userID" class="form-control" required>
              </div>

              <div class="col-12">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>

              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">
                  Login
                </button>
              </div>
            </form>
          </div>
        </div>



      </div>
    </div>
  </div>
</section>
@endsection