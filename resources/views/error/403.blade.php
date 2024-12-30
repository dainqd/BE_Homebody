@extends('error.layouts.master')
@section('title')
    403 Forbidden
@endsection
@section('content')
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>403</h1>
            <h2>Forbidden Error.</h2>
            <a class="btn" href="{{ route('auth.web.processLogin') }}?url_callback={{$callback}}">Back To Home</a>
            <img src="{{ asset('admin/img/not-found.svg') }}" class="img-fluid py-5" alt="Forbidden">
            <div class="credits">
                Designed by <a href="#">Dev Fullstack</a>
            </div>
        </section>

    </div>
@endsection
