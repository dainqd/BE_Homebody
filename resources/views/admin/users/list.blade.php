@extends('admin.layouts.master')
@section('title')
    List User
@endsection
@section('content')
    <div class="pagetitle">
        <h1>User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">List User</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-hover">
            <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="x">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Role</th>
                <th scope="col">Address</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->role_name }}</td>
                    <td>
                        <div class="text-truncate">{{ $user->address }}</div>
                    </td>
                    <td>{{ $user->status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.detail', $user->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $user->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links('pagination::bootstrap-5') }}
    </section>

    <script>
        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete partner?')) {
                deletePartner(id);
            }
        })

        async function deletePartner(id) {
            loadingPage();

            let url = `{{ route('api.admin.users.delete', ':id') }}`;
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    'id': id
                },
                dataType: 'json',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + accessToken,
                },
                async: false,
                success: function (data, textStatus) {
                    alert('Delete user successfully');
                    loadingPage();
                    console.log(data)
                    window.location.reload();
                },
                error: function (request, status, error) {
                    let data = JSON.parse(request.responseText);
                    alert(data.message);
                    loadingPage();
                }
            });
        }
    </script>
@endsection
