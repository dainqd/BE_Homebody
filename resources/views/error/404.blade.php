@extends('error.layouts.master')
@section('title')
    Không tìm thấy 404
@endsection
@section('content')
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>404</h1>
            <h2>Trang bạn đang tìm kiếm không tồn tại.</h2>
            <a class="btn" href="#">Quay lại trang chủ</a>
            <img src="{{ asset('admin/img/not-found.svg') }}" class="img-fluid py-5" alt="Page Not Found">
            <div class="credits">
                Thiết kế bởi <a href="#">Dev Fullstack</a>
            </div>
        </section>

    </div>
@endsection
