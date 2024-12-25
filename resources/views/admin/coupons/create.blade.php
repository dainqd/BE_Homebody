@extends('admin.layouts.master')
@section('title')
    Create Coupon
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Coupon</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Coupon</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form id="formCreate">
            <div class="form-group">
                <label for="name">Name Coupon</label>
                <input type="text" class="form-control" name="name" id="name"
                       placeholder="Please enter name coupon">
            </div>
            <div class="form-group">
                <label for="type">Type Coupon</label>
                <input type="text" class="form-control" name="type" id="type"
                       placeholder="Please enter type coupon">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="discount_percent">Discount Percent(user %)</label>
                    <input type="number" min="0" max="100" class="form-control" name="discount_percent"
                           id="discount_percent">
                </div>
                <div class="form-group col-md-4">
                    <label for="max_discount">Max Discount</label>
                    <input type="number" min="0" class="form-control" name="max_discount" id="max_discount">
                </div>
                <div class="form-group col-md-4">
                    <label for="min_total">Min total price</label>
                    <input type="number" min="0" class="form-control" name="min_total" id="min_total">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="quantity">Quantity</label>
                    <input type="number" min="1" class="form-control" name="quantity" id="quantity">
                </div>
                <div class="form-group col-md-6">
                    <label for="max_set">Maximum number per account</label>
                    <input type="number" min="1" class="form-control" name="max_set" id="max_set">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" class="form-control" name="thumbnail" id="thumbnail">
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option
                            value="{{ \App\Enums\CouponStatus::ACTIVE }}">{{ \App\Enums\CouponStatus::ACTIVE }}</option>
                        <option
                            value="{{ \App\Enums\CouponStatus::INACTIVE }}">{{ \App\Enums\CouponStatus::INACTIVE }}</option>
                    </select>
                </div>
            </div>
            <button type="button" id="btnCreate" class="btn btn-primary mt-3" onclick="createCoupon();">Create
            </button>
        </form>
    </section>
    <script>
        async function createCoupon() {
            let token = `Bearer ` + accessToken;
            let headers = {
                "Authorization": token
            };

            let couponUrl = '{{ route('api.admin.coupons.create') }}';
            loadingPage();

            $('#btnCreate').prop('disabled', true).text('Creating...');

            let inputs = $('#formCreate input, #formCreate textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val()) {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnCreate').prop('disabled', false).text('Create');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formCreate')[0]);

            await $.ajax({
                url: couponUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Create success!');
                    window.location.href = `{{ route('admin.coupons.list') }}`;
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnCreate').prop('disabled', false).text('Create');
                }
            });
        }
    </script>
@endsection
