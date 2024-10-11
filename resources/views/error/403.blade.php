@extends('error.layouts.master')
@section('title')
    Không đủ thẩm quyền
@endsection
@section('content')
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>403</h1>
            <h2>Không đủ thẩm quyền.</h2>
            <a class="btn" href="#">Quay lại trang chủ</a>
            <img src="{{ asset('admin/img/not-found.svg') }}" class="img-fluid py-5" alt="Forbidden">
            <div class="credits">
                Thiết kế bởi <a href="#">Dev Fullstack</a>
            </div>
        </section>

    </div>
@endsection
