@extends('admin.layouts.master')
@section('title')
    List Coupon
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Coupon</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">List Coupon</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-hover">
            <colgroup>
                <col width="5%">
                <col width="x">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Code</th>
                <th scope="col">Type</th>
                <th scope="col">Quantity</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $coupon->name }}</td>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->type }}</td>
                    <td>{{ $coupon->quantity }}</td>
                    <td>{{ $coupon->start_time }}</td>
                    <td>{{ $coupon->end_time }}</td>
                    <td>{{ $coupon->status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.coupons.detail', $coupon->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $coupon->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $coupons->links('pagination::bootstrap-5') }}
    </section>

    <script>
        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete coupon?')) {
                deletePartner(id);
            }
        })

        async function deletePartner(id) {
            loadingPage();

            let url = `{{ route('api.admin.coupons.delete', ':id') }}`;
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
                    alert('Delete coupon successfully');
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
