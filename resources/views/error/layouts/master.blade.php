<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>

    <!-- Favicons -->
    <link href="{{ setting() ? setting()->favicon : asset('admin/img/favicon.png') }}" rel="icon">
    <link href="{{ setting() ? setting()->favicon : asset('admin/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Meta tag seo -->
    <meta name="description" content="{{ setting() ? setting()->meta_tag : '' }}">
    <meta name="keywords" content="{{ setting() ? setting()->meta_keyword : '' }}">
    <meta name="robots" content="index, follow">

    <meta name="google-site-verification" content="{{ setting() ? setting()->og_site : 'bhZ6nlhhqPi_NXa_Rbjp3drDJrxMwRd9BBxjALzVL8I' }}" />

    <!-- Open Graph (Facebook, LinkedIn) -->
    <meta property="og:title" content="{{ setting() ? setting()->og_title : '' }}">
    <meta property="og:description" content="{{ setting() ? setting()->og_des : '' }}">
    <meta property="og:image" content="{{ setting() ? setting()->og_img : '' }}">
    <meta property="og:url" content="{{ setting() ? setting()->og_url : '' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ setting() ? setting()->og_site : '' }}">
    <meta name="twitter:title" content="{{ setting() ? setting()->og_title : '' }}">
    <meta name="twitter:description" content="{{ setting() ? setting()->og_des : '' }}">
    <meta name="twitter:image" content="{{ setting() ? setting()->og_img : '' }}">

    <!-- Meta Author -->
    <meta name="author" content="{{ setting() ? setting()->owner_name : '' }}">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Template Main CSS File -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
</head>

<body>
@include('sweetalert::alert')
<!-- ======= Main ======= -->
<main>

    @yield('content')

</main>
<!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('admin/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('admin/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('admin/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('admin/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('admin/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('admin/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('admin/js/main.js') }}"></script>

</body>

</html>
