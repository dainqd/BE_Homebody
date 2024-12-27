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
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Subject</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contacts as $contact)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $contact->first_name }}</td>
                    <td>{{ $contact->last_name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->subject }}</td>
                    <td>{{ $contact->status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.contacts.detail', $contact->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $contact->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $contacts->links('pagination::bootstrap-5') }}
    </section>

    <script>

        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete contact?')) {
                updatePartner(id);
            }
        })

        async function updatePartner(id) {
            loadingPage();

            let url = `{{ route('api.admin.contacts.delete', ['id'=>':id']) }}`;
            let method = 'DELETE';

            url = url.replace(':id', id);

            let data = {
                'status': '{{ \App\Enums\ContactStatus::DELETED }}'
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
                    alert('Delete successfully');
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
