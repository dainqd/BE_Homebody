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
        <form id="formUpdate">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" name="full_name" id="full_name"
                       value="{{ $user->full_name }}" placeholder="Please enter name...">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username"
                           id="username" value="{{ $user->username }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{ $user->phone }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}">
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
                       placeholder="Please enter address..." value="{{ $user->address }}">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control">
                        <option {{  $role->name == \App\Enums\RoleName::PARTNER ?  'selected' : ''}}
                                value="{{ \App\Enums\RoleName::PARTNER }}">{{ \App\Enums\RoleName::PARTNER }}</option>
                        <option {{  $role->name == \App\Enums\RoleName::USER ?  'selected' : ''}}
                                value="{{ \App\Enums\RoleName::USER }}">{{ \App\Enums\RoleName::USER }}</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="avatar">Avatar</label>
                    <input type="file" class="form-control" name="avatar" id="avatar">
                    <img src="{{ $user->avatar }}" alt="" class="mt-2" style="width: 200px">
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option {{  $user->status == \App\Enums\UserStatus::ACTIVE ?  'selected' : ''}}
                                value="{{ \App\Enums\UserStatus::ACTIVE }}">{{ \App\Enums\UserStatus::ACTIVE }}</option>
                        <option {{  $user->status == \App\Enums\UserStatus::INACTIVE ?  'selected' : ''}}
                                value="{{ \App\Enums\UserStatus::INACTIVE }}">{{ \App\Enums\UserStatus::INACTIVE }}</option>
                        <option {{  $user->status == \App\Enums\UserStatus::BLOCKED ?  'selected' : ''}}
                                value="{{ \App\Enums\UserStatus::BLOCKED }}">{{ \App\Enums\UserStatus::BLOCKED }}</option>
                    </select>
                </div>
            </div>
            <button type="button" id="btnUpdate" class="btn btn-primary mt-3" onclick="updateUser();">
                Save Changes
            </button>
        </form>
    </section>
    <script>
        async function updateUser() {
            let token = `Bearer ` + accessToken;
            let headers = {
                "Authorization": token
            };

            let categoryUrl = '{{ route('api.admin.users.update', $user->id) }}';
            loadingPage();

            $('#btnUpdate').prop('disabled', true).text('Saving...');

            let inputs = $('#formUpdate input, #formUpdate textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val() && $(inputs[i]).attr('type') !== 'file' && $(inputs[i]).attr('type') !== 'password' && $(inputs[i]).attr('type') !== 'hidden') {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnUpdate').prop('disabled', false).text('Save Changes');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formUpdate')[0]);

            await $.ajax({
                url: categoryUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Update success!');
                    window.location.href = `{{ route('admin.users.list') }}`;
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnUpdate').prop('disabled', false).text('Save Changes');
                }
            });
        }
    </script>
@endsection
