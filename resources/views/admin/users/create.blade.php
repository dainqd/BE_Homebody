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
        <form id="formCreate">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" name="full_name" id="full_name"
                       placeholder="Please enter name...">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username"
                           id="username">
                </div>
                <div class="form-group col-md-4">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                </div>
                <div class="form-group col-md-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="form-group col-md-6">
                    <label for="password_confirm">Password Confirm</label>
                    <input type="password" class="form-control" name="password_confirm" id="password_confirm">
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" name="address" id="address"
                       placeholder="Please enter address...">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control">
                        <option value="{{ \App\Enums\RoleName::PARTNER }}">{{ \App\Enums\RoleName::PARTNER }}</option>
                        <option value="{{ \App\Enums\RoleName::USER }}">{{ \App\Enums\RoleName::USER }}</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="avatar">Avatar</label>
                    <input type="file" class="form-control" name="avatar" id="avatar">
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option
                            value="{{ \App\Enums\UserStatus::ACTIVE }}">{{ \App\Enums\UserStatus::ACTIVE }}</option>
                        <option
                            value="{{ \App\Enums\UserStatus::INACTIVE }}">{{ \App\Enums\UserStatus::INACTIVE }}</option>
                        <option
                            value="{{ \App\Enums\UserStatus::BLOCKED }}">{{ \App\Enums\UserStatus::BLOCKED }}</option>
                    </select>
                </div>
            </div>
            <button type="button" id="btnCreate" class="btn btn-primary mt-3" onclick="createUser();">Create
            </button>
        </form>
    </section>
    <script>
        async function createUser() {
            let token = `Bearer ` + accessToken;
            let headers = {
                "Authorization": token
            };


            let categoryUrl = '{{ route('api.admin.users.create') }}';
            loadingPage();

            $('#btnCreate').prop('disabled', true).text('Creating...');

            let inputs = $('#formCreate input, #formCreate textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val()) {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnCreate').prop('disabled', false).text('Create');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formCreate')[0]);

            await $.ajax({
                url: categoryUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Create success!');
                    window.location.href = `{{ route('admin.users.list') }}`;
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnCreate').prop('disabled', false).text('Create');
                }
            });
        }
    </script>
@endsection
