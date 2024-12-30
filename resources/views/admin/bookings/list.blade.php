@extends('admin.layouts.master')
@section('title')
    List Booking
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Booking</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">List Booking</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-hover">
            <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="15%">
                <col width="20%">
                <col width="5%">
                <col width="10%">
                <col width="10%">
                <col width="5%">
                <col width="x">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email/Phone</th>
                <th scope="col">Address</th>
                <th scope="col">Type</th>
                <th scope="col">Check In</th>
                <th scope="col">Total Price</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $booking->name }}</td>
                    <td>{{ $booking->email }}/{{ $booking->phone }}</td>
                    <td>{{ $booking->address }}</td>
                    <td>{{ $booking->type }}</td>
                    <td>{{ $booking->check_in }}</td>
                    <td>{{ $booking->main_total_price }}</td>
                    <td>{{ $booking->status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.bookings.detail', $booking->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $booking->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $bookings->links('pagination::bootstrap-5') }}
    </section>

    <script>

        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete booking?')) {
                updatePartner(id);
            }
        })

        async function updatePartner(id) {
            loadingPage();

            let url = `{{ route('api.admin.bookings.delete', ['id'=>':id']) }}`;
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
