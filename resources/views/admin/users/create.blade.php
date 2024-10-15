@extends('admin.layouts.master')
@section('title')
    Create User
@endsection
@section('content')
    <div class="pagetitle">
        <h1>User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Create User</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-bordered">

        </table>
    </section>
    <script>
        async function createUser() {
            loadingPage();


        }
    </script>
@endsection
