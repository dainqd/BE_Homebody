@extends('admin.layouts.master')
@section('title')
    Detail Booking
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Booking</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Booking</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form action="#" id="formUpdateBooking">
            <div class="row">
                <div class="col-xl-8 col-md-7 col-sm-12">
                    <h5 class="">Booking Information</h5>
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="40%">
                            <col width="10%">
                            <col width="40%">
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="row">Name</th>
                            <td colspan="3">{{ $booking->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td>{{ $booking->email }}</td>
                            <th scope="row">Phone</th>
                            <td>{{ $booking->phone }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Address</th>
                            <td colspan="3">{{ $booking->address }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Type</th>
                            <td>{{ $booking->type }}</td>
                            <th scope="row">Status</th>
                            <td>{{ $booking->status }}</td>
                        </tr>
                        <tr>
                            <th scope="row">CheckIn Time</th>
                            <td>{{ $booking->check_in }}</td>
                            <th scope="row">Created Time</th>
                            <td>{{ $booking->created_at }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Notes</th>
                            <td colspan="3">{{ $booking->notes }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Price</th>
                            <th scope="row">Discount</th>
                            <th scope="row">Vat</th>
                            <th scope="row">Total</th>
                        </tr>
                        <tr>
                            <td>{{ $booking->total_price }}</td>
                            <td>{{ $booking->discount_price }}</td>
                            <td>{{ $booking->vat }}</td>
                            <td>{{ $booking->main_total_price }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xl-4 col-md-5 col-sm-12">
                    <h5 class="">Booking Service</h5>
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="15%">
                            <col width="35%">
                            <col width="15%">
                            <col width="35%">
                        </colgroup>
                        <tbody>
                        @foreach($booking_services as $booking_service)
                            <tr>
                                <th colspan="4" scope="row">
                                    {{ $loop->index + 1 }}. {{ $booking_service['service']['name'] }}
                                </th>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td>{{ $booking_service['quantity'] }}</td>
                                <th>Price</th>
                                <td>{{ $booking_service['price'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <h5 class="mt-4">Booking Histories</h5>
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="30%">
                            <col width="30%">
                            <col width="30%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Status</th>
                            <th>Updated By</th>
                            <th>Updated Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($booking_histories as $booking_history)
                            <tr>
                                <td>
                                    {{ $loop->index +1 }}
                                </td>
                                <td>{{ $booking_history->status }}</td>
                                <td>{{ $booking_history->full_name ?? $booking_history->email}}</td>
                                <td>{{ $booking_history->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </section>
    <script>
        $('.btnApprove').on('click', function () {
            if (confirm('Are you want to approve booking?')) {
                updatePartner('approve', 'update');
            }
        })

        $('.btnReject').on('click', function () {
            if (confirm('Are you want to reject booking?')) {
                updatePartner('reject', 'update');
            }
        });

        $('.btnDelete').on('click', function () {
            if (confirm('Are you want to delete booking?')) {
                updatePartner('delete', 'delete');
            }
        });

        async function updatePartner(type, mode) {
            await loadingPage();
            let status;
            let update = 'N';
            if (type === 'approve') {
                status = `{{ \App\Enums\BookingStatus::CONFIRMED }}`;
            } else {
                status = `{{ \App\Enums\BookingStatus::CANCELED }}`;
            }

            let url;
            let method;
            if (mode === 'update') {
                url = `{{ route('api.admin.bookings.update', $booking->id) }}`;
                method = 'POST';
            } else {
                url = `{{ route('api.admin.bookings.delete', $booking->id) }}`;
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
