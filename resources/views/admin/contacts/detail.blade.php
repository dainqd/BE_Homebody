@extends('admin.layouts.master')
@section('title')
    Detail Partner Register
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Partner Register</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Partner Register</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th scope="row">Name</th>
                <td>{{ $partner->name }}</td>
                <th scope="row">Status</th>
                <td>{{ $partner->status }}</td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td>{{ $partner->email }}</td>
                <th scope="row">Created At</th>
                <td>{{ $partner->created_at }}</td>
            </tr>
            <tr>
                <th scope="row">Phone</th>
                <td>{{ $partner->phone }}</td>
                <th scope="row">Updated At</th>
                <td>{{ $partner->updated_at }}</td>
            </tr>
            @if($partner->status == \App\Enums\PartnerRegisterStatus::PENDING)
                <tr>
                    <td colspan="4">
                        <button class="btn btn-success btnApproveAndCreate" type="button">Approve and Create</button>
                        <button class="btn btn-primary btnApprove" type="button">Approve</button>
                        <button class="btn btn-warning btnReject" type="button">Reject</button>
                        <button class="btn btn-danger btnDelete" type="button">Delete</button>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </section>
    <script>
        $('.btnApproveAndCreate').on('click', function () {
            if (confirm('Are you want to approve and create partner?')) {
                updatePartner('approve_create', 'update');
            }
        });
        $('.btnApprove').on('click', function () {
            if (confirm('Are you want to approve partner?')) {
                updatePartner('approve', 'update');
            }
        })

        $('.btnReject').on('click', function () {
            if (confirm('Are you want to reject partner?')) {
                updatePartner('reject', 'update');
            }
        });

        $('.btnDelete').on('click', function () {
            if (confirm('Are you want to delete partner?')) {
                updatePartner('delete', 'delete');
            }
        });

        async function updatePartner(type, mode) {
            await loadingPage();
            let status;
            let update = 'N';
            if (type === 'approve') {
                status = `{{ \App\Enums\PartnerRegisterStatus::APPROVED }}`;
            } else if (type === 'approve_create') {
                status = `{{ \App\Enums\PartnerRegisterStatus::APPROVED }}`;
                update = 'Y';
            } else {
                status = `{{ \App\Enums\PartnerRegisterStatus::REJECTED }}`;
            }

            let url;
            let method;
            if (mode === 'update') {
                url = `{{ route('api.admin.partner.register.update', $partner->id) }}`;
                method = 'POST';
            } else {
                url = `{{ route('api.admin.partner.register.delete', $partner->id) }}`;
                method = 'DELETE';
            }

            let data = {
                'status': status,
                'update': update
            }

            data = JSON.stringify(data);

            $.ajax({
                url: url,
                type: method,
                data: data,
                dataType: 'json',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + accessToken,
                },
                async: false,
                success: function (data, textStatus) {
                    alert('Partner register updated successfully');
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
