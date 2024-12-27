@extends('admin.layouts.master')
@section('title')
    List Contact
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Contact</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">List Contact</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-hover">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="10%">
                <col width="x">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($partners as $partner)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $partner->name }}</td>
                    <td>{{ $partner->email }}</td>
                    <td>{{ $partner->phone }}</td>
                    <td>{{ $partner->status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.partner.register.detail', $partner->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $partner->id }}" class="btn btn-primary btnApprove">
                                Approve
                            </button>
                            <button type="button" data-id="{{ $partner->id }}" class="btn btn-warning btnCancel">
                                Reject
                            </button>
                            <button type="button" data-id="{{ $partner->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $partners->links('pagination::bootstrap-5') }}
    </section>

    <script>
        $('.btnApprove').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to approve partner?')) {
                updatePartner('approve', 'update', id);
            }
        })

        $('.btnCancel').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to reject partner?')) {
                updatePartner('reject', 'update', id);
            }
        })

        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete partner?')) {
                updatePartner('delete', 'delete', id);
            }
        })

        async function updatePartner(type, mode, id) {
            loadingPage();
            let status;
            if (type === 'approve') {
                status = `{{ \App\Enums\PartnerRegisterStatus::APPROVED }}`;
            } else {
                status = `{{ \App\Enums\PartnerRegisterStatus::REJECTED }}`;
            }

            let url;
            let method;
            if (mode === 'update') {
                url = `{{ route('api.admin.partner.register.update', ['id'=>':id']) }}`;
                method = 'POST';
            } else {
                url = `{{ route('api.admin.partner.register.delete', ['id'=>':id']) }}`;
                method = 'DELETE';
            }

            url = url.replace(':id', id);

            let data = {
                'status': status
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
