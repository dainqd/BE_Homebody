@extends('error.layouts.master')
@section('title')
    Login
@endsection
@section('content')
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="#" class="logo d-flex align-items-center w-auto">
                                <img src="{{ asset('admin/img/logo.png') }}" alt="">
                                <span class="d-none d-lg-block">Dev Fullstack</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login your account</h5>
                                    <p class="text-center small">Enter your email or phone number & password to
                                        login</p>
                                </div>

                                <form class="row g-3 needs-validation" novalidate method="post"
                                      action="{{ route('auth.web.handleLogin') }}">
                                    @csrf
                                    <div class="col-12">
                                        <label for="login_request" class="form-label">Email or Phone number</label>
                                        <input type="text" name="login_request" class="form-control" id="login_request"
                                               required>
                                        <div class="invalid-feedback">Please enter your email or phone number.</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="yourPassword"
                                               required>
                                        <div class="invalid-feedback">Please enter your password!</div>
                                    </div>

                                    <input type="hidden" name="url_callback" id="url_callback" value="">

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Login</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="credits">
                            Design by <a href="#">Dev Fullstack</a>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
@endsection
