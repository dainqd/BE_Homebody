@extends('admin.layouts.master')
@section('title')
    Detail Coupon
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Coupon</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Coupon</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form id="formUpdate">
            <div class="form-group">
                <label for="name">Name Coupon</label>
                <input type="text" class="form-control" name="name" id="name"
                       value="{{ $coupon->name }}"
                       placeholder="Please enter name coupon">
            </div>
            <div class="form-group">
                <label for="type">Type Coupon</label>
                <input type="text" class="form-control" name="type" id="type"
                       value="{{ $coupon->type }}"
                       placeholder="Please enter type coupon">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="discount_percent">Discount Percent(user %)</label>
                    <input type="number" min="0" max="100" class="form-control" name="discount_percent"
                           id="discount_percent" value="{{ $coupon->discount_percent }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="max_discount">Max Discount</label>
                    <input type="number" min="0" class="form-control" name="max_discount" id="max_discount"
                           value="{{ $coupon->max_discount }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="min_total">Min total price</label>
                    <input type="number" min="0" class="form-control" name="min_total" id="min_total"
                           value="{{ $coupon->min_total }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="quantity">Quantity</label>
                    <input type="number" min="1" class="form-control" name="quantity" id="quantity"
                           value="{{ $coupon->quantity }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="max_set">Maximum number per account</label>
                    <input type="number" min="1" class="form-control" name="max_set" id="max_set"
                           value="{{ $coupon->max_set }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" class="form-control" name="thumbnail" id="thumbnail">
                    <img src="{{ $coupon->thumbnail }}" alt="" class="mt-2" style="width: 200px">
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option {{  $coupon->status == \App\Enums\CouponStatus::ACTIVE ?  'selected' : ''}}
                                value="{{ \App\Enums\CouponStatus::ACTIVE }}">{{ \App\Enums\CouponStatus::ACTIVE }}</option>
                        <option {{  $coupon->status == \App\Enums\CouponStatus::ACTIVE ?  'selected' : ''}}
                                value="{{ \App\Enums\CouponStatus::INACTIVE }}">{{ \App\Enums\CouponStatus::INACTIVE }}</option>
                    </select>
                </div>
            </div>

            <button type="button" id="btnUpdate" class="btn btn-primary mt-3" onclick="updateCoupon();">
                Save Changes
            </button>
        </form>
    </section>
    <script>
        let token = `Bearer ` + accessToken;
        let headers = {
            "Authorization": token
        };

        async function updateCoupon() {
            let categoryUrl = '{{ route('api.admin.coupons.update', $coupon->id) }}';
            loadingPage();

            $('#btnUpdate').prop('disabled', true).text('Saving...');

            let inputs = $('#formUpdate input, #formUpdate textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val() && $(inputs[i]).attr('type') !== 'file' && $(inputs[i]).attr('type') !== 'hidden') {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnUpdate').prop('disabled', false).text('Save Changes');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formUpdate')[0]);

            await $.ajax({
                url: categoryUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Update success!');
                    window.location.href = `{{ route('admin.coupons.list') }}`;
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnUpdate').prop('disabled', false).text('Save Changes');
                }
            });
        }
    </script>
@endsection
