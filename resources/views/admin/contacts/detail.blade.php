@extends('admin.layouts.master')
@section('title')
    Detail Contact
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Contact</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Contact</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-bordered">
            <colgroup>
                <col width="10%">
                <col width="40%">
                <col width="10%">
                <col width="40%">
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">First Name</th>
                <td>{{ $contact->first_name }}</td>
                <th scope="row">Last Name</th>
                <td>{{ $contact->last_name }}</td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td>{{ $contact->email }}</td>
                <th scope="row">Subject</th>
                <td>{{ $contact->subject }}</td>
            </tr>
            <tr>
                <th scope="row">Created Time</th>
                <td>{{ $contact->created_at }}</td>
                <th scope="row">Status</th>
                <td>{{ $contact->status }}</td>
            </tr>
            <tr>
                <th scope="row">Message</th>
                <td colspan="3">{{ $contact->message }}</td>
            </tr>
            @if($contact->status == \App\Enums\ContactStatus::PENDING)
                <tr>
                    <td colspan="4">
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
        $('.btnApprove').on('click', function () {
            if (confirm('Are you want to approve contact?')) {
                updatePartner('approve', 'update');
            }
        })

        $('.btnReject').on('click', function () {
            if (confirm('Are you want to reject contact?')) {
                updatePartner('reject', 'update');
            }
        });

        $('.btnDelete').on('click', function () {
            if (confirm('Are you want to delete contact?')) {
                updatePartner('delete', 'delete');
            }
        });

        async function updatePartner(type, mode) {
            await loadingPage();
            let status;
            let update = 'N';
            if (type === 'approve') {
                status = `{{ \App\Enums\ContactStatus::APPROVED }}`;
            } else {
                status = `{{ \App\Enums\ContactStatus::REJECTED }}`;
            }

            let url;
            let method;
            if (mode === 'update') {
                url = `{{ route('api.admin.contacts.update', $contact->id) }}`;
                method = 'POST';
            } else {
                url = `{{ route('api.admin.contacts.delete', $contact->id) }}`;
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
                    if (mode === 'update') {
                        alert('Contact updated successfully');
                        loadingPage();
                        console.log(data)
                        window.location.reload();
                    } else {
                        alert('Contact delete successfully');
                        loadingPage();
                        console.log(data)
                        window.location.href = '{{ route('admin.contacts.list') }}';
                    }
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
