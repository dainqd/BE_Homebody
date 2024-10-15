@extends('admin.layouts.master')
@section('title')
    Detail User
@endsection
@section('content')
    <div class="pagetitle">
        <h1>User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail User</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-bordered">

        </table>
    </section>
    <script>
        async function updateUser() {
            loadingPage();

        }
    </script>
@endsection
