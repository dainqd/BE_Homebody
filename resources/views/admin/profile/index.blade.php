@extends('admin.layouts.master')
@section('title')
    Profile
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ Auth::user()->avatar }}" alt="Profile" class="rounded-circle">
                        <h2>{{ Auth::user()->full_name }}</h2>
                        <h3>{{ Auth::user()->username }}</h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">
                                    Overview
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                    Profile
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">
                                    Settings
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">
                                    Change Password
                                </button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8"> {{ Auth::user()->full_name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Username</div>
                                    <div class="col-lg-9 col-md-8"> {{ Auth::user()->username }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8"> {{ Auth::user()->address }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8"> {{ Auth::user()->phone }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8"> {{ Auth::user()->email }}</div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <form enctype="multipart/form-data" id="formUpdateProfile">
                                    <div class="d-none">
                                        <input type="file" name="avatar" id="inputAvatar">
                                    </div>
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                            Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="{{ Auth::user()->avatar }}" alt="Profile" id="imagePreview">
                                            <div class="pt-2">
                                                <a href="#" class="btn btn-primary btn-sm btnUploadImage"
                                                   title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm btnRemoveImage"
                                                   title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Full
                                            Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="full_name" type="text" class="form-control" id="full_name"
                                                   value="{{ Auth::user()->full_name }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="username" type="text" class="form-control" id="username"
                                                   value="{{ Auth::user()->username }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="address" type="text" class="form-control" id="address"
                                                   value="{{ Auth::user()->address }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="phone" type="text" class="form-control" id="phone"
                                                   value="{{ Auth::user()->username }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="email"
                                                   value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button onclick="updateProfile();" type="button" class="btn btn-primary"
                                                id="btnUpdateProfile">
                                            Save Changes
                                        </button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-settings">

                                <!-- Settings Form -->
                                <form>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email
                                            Notifications</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="changesMade"
                                                       checked>
                                                <label class="form-check-label" for="changesMade">
                                                    Changes made to your account
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="newProducts"
                                                       checked>
                                                <label class="form-check-label" for="newProducts">
                                                    Information on new products and services
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="proOffers">
                                                <label class="form-check-label" for="proOffers">
                                                    Marketing and promo offers
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="securityNotify"
                                                       checked disabled>
                                                <label class="form-check-label" for="securityNotify">
                                                    Security alerts
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End settings Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form id="formChangePassword">

                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control"
                                                   id="currentPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword" type="password" class="form-control"
                                                   id="newPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renewpassword" type="password" class="form-control"
                                                   id="renewPassword">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button onclick="changePassword();" type="button" class="btn btn-primary">
                                            Change Password
                                        </button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>
    <script>
        $(document).ready(function () {
            $('.btnUploadImage').click(function () {
                $('#inputAvatar').click();
            })

            const imgInp = document.getElementById('inputAvatar');
            const imagePreview = document.getElementById('imagePreview');

            imgInp.onchange = evt => {
                const [file] = imgInp.files
                if (file) {
                    imagePreview.src = URL.createObjectURL(file)
                }
            }

            $('.btnRemoveImage').click(function () {
                imagePreview.src = '{{ Auth::user()->avatar }}';
                $('#inputAvatar').val('');
            })
        })

        async function updateProfile() {
            let token = `Bearer ` + accessToken;
            let headers = {
                "Authorization": token
            };

            loadingPage();
            let apiUrl = '{{ route('api.users.update.info') }}';

            $('#btnUpdateProfile').prop('disabled', true).text('Saving Changes...');

            let inputs = $('#formUpdateProfile input, #formUpdateProfile textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val() && $(inputs[i]).attr('type') != 'file') {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnUpdateProfile').prop('disabled', false).text('Save Changes');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formUpdateProfile')[0]);

            await $.ajax({
                url: apiUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Update profile success!');
                    window.location.reload()
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnUpdateProfile').prop('disabled', false).text('Save Changes');
                }
            });
        }

        async function changePassword() {
            let token = `Bearer ` + accessToken;
            let headers = {
                "Authorization": token
            };

            loadingPage();
            let apiUrl = '{{ route('api.users.change.password') }}';

            $('#btnChangePassword').prop('disabled', true).text('Saving Changes...');

            let inputs = $('#formChangePassword input');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val()) {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnChangePassword').prop('disabled', false).text('Save Changes');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formChangePassword')[0]);

            await $.ajax({
                url: apiUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Change password success!');
                    window.location.reload()
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnChangePassword').prop('disabled', false).text('Save Changes');
                }
            });
        }
    </script>
@endsection
