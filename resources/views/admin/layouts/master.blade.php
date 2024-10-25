<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('admin/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>

    <style>
        .layout_loading {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .layout_loading.open {
            display: flex;
        }

    </style>
    <script>
        const accessToken = `{{ $_COOKIE['accessToken'] ?? '' }}`;

        function loadingPage() {
            let layout_loading = $('#layout_loading');

            if (layout_loading.hasClass('open')) {
                layout_loading.removeClass('open');
            } else {
                layout_loading.addClass('open');
            }
        }
    </script>
</head>

<body>

<!-- ======= Header ======= -->
@include('admin.layouts.header')
<!-- End Header -->

<!-- ======= Sidebar ======= -->
@include('admin.layouts.sidebar')
<!-- End Sidebar-->

@include('sweetalert::alert')

<div class="layout_loading" id="layout_loading">
    <div class="spinner-border" role="status">
    </div>
</div>

<!-- ======= Main ======= -->
<main id="main" class="main">

    @yield('content')

</main>
<!-- End #main -->

<!-- ======= Footer ======= -->
@include('admin.layouts.footer')
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

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
<style>
    .tox.tox-tinymce {
        z-index: 0;
    }
</style>
<script>
    tinymce.init({
        selector: 'textarea:not(div.composer textarea)',
        plugins: 'link image media',
        toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | image | table ',
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            /*
              Note: In modern browsers input[type="file"] is functional without
              even adding it to the DOM, but that might not be the case in some older
              or quirky browsers like IE, so you might want to add it to the DOM
              just in case, and visually hide it. And do not forget do remove it
              once you do not need it anymore.
            */

            input.onchange = function () {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function () {
                    /*
                      Note: Now we need to register the blob in TinyMCEs image blob
                      registry. In the next release this part hopefully won't be
                      necessary, as we are looking to handle it internally.
                    */
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), {title: file.name});
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
    });
</script>
</body>

</html>
