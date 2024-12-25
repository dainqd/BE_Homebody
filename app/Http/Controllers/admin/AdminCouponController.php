<?php

namespace App\Http\Controllers\admin;

use App\Enums\CouponStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCouponController extends Controller
{
    public function list()
    {
        try {
            $coupons = Coupons::where('status', '!=', CouponStatus::DELETED)
                ->orderByDesc('id')
                ->paginate(10);
            return view('admin.coupons.list', compact('coupons'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function detail($id)
    {
        try {
            $coupon = Coupons::where('status', '!=', CouponStatus::DELETED)
                ->where('id', $id)
                ->first();

            if (!$coupon) {
                return redirect(route('error.not.found'));
            }

            return view('admin.coupons.detail', compact('coupon'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function create()
    {
        try {
            return view('admin.coupons.create');
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $coupon = new Coupons();
            $coupon = $this->save($request, $coupon);

            $coupon->save();

            alert()->success('Create coupon success!');
            return redirect(route(''));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $coupon = Coupons::where('status', '!=', CouponStatus::DELETED)
                ->where('id', $id)
                ->first();

            if (!$coupon) {
                return redirect(route('error.not.found'));
            }

            $coupon = $this->save($request, $coupon);

            $coupon->save();

            alert()->success('Update coupon success!');
            return redirect(route(''));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function delete($id)
    {
        try {
            $coupon = Coupons::where('status', '!=', CouponStatus::DELETED)
                ->where('id', $id)
                ->first();

            if (!$coupon) {
                return redirect(route('error.not.found'));
            }

            $coupon->status = CouponStatus::DELETED;
            $coupon->save();

            alert()->success('Delete coupon success!');
            return redirect(route(''));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    private function save(Request $request, Coupons $coupon)
    {
        $name = $request->input('name');
        $name_en = language_helper($name, 'en');
        $name_cn = language_helper($name, 'zh-CN');
        $name_vi = language_helper($name, 'vi');

        $description = $request->input('description');
        $description_en = language_helper($description, 'en');
        $description_cn = language_helper($description, 'zh-CN');
        $description_vi = language_helper($description, 'vi');

        $type = $request->input('type');

        $discount_percent = $request->input('discount_percent');

        $max_discount = $request->input('max_discount');
        $max_discount_en = $request->input('max_discount_en') ?? 0;
        $max_discount_cn = $request->input('max_discount_cn') ?? 0;
        $max_discount_vi = $request->input('max_discount_vi') ?? 0;

        $max_set = $request->input('max_set');

        $status = $request->input('status') ?? CouponStatus::ACTIVE;

        $quantity = $request->input('quantity');
        $number_used = $request->input('number_used') ?? 0;

        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        $min_total = $request->input('min_total');
        $min_total_en = $request->input('min_total_en') ?? 0;
        $min_total_cn = $request->input('min_total_cn') ?? 0;
        $min_total_vi = $request->input('min_total_vi') ?? 0;

        $created_by = Auth::user()->id;

        if ($name != $coupon->name) {
            $coupon->name = $name;
            $coupon->name_en = $name_en;
            $coupon->name_cn = $name_cn;
            $coupon->name_vi = $name_vi;
        }

        if (!$coupon->code) {
            $code = (new MainController())->generateRandomString(8);

            $isValid = false;
            do {
                $existCoupon = Coupons::where('code', $code)->first();
                if (!$existCoupon) {
                    $isValid = true;
                } else {
                    $code = (new MainController())->generateRandomString(8);
                }
            } while (!$isValid);

            $coupon->code = $code;
        }

        if ($request->hasFile('thumbnail')) {
            $item = $request->file('thumbnail');
            $itemPath = $item->store('coupon', 'public');
            $thumbnail = asset('storage/' . $itemPath);
            $coupon->thumbnail = $thumbnail;
        }

        $coupon->description = $description;
        $coupon->description_en = $description_en;
        $coupon->description_cn = $description_cn;
        $coupon->description_vi = $description_vi;
        $coupon->type = $type;
        $coupon->discount_percent = $discount_percent;
        $coupon->max_discount = $max_discount;
        $coupon->max_discount_en = $max_discount_en;
        $coupon->max_discount_cn = $max_discount_cn;
        $coupon->max_discount_vi = $max_discount_vi;
        $coupon->max_set = $max_set;
        $coupon->status = $status;
        $coupon->quantity = $quantity;
        $coupon->number_used = $number_used;
        $coupon->start_time = $start_time;
        $coupon->end_time = $end_time;
        $coupon->created_by = $created_by;
        $coupon->min_total = $min_total;
        $coupon->min_total_en = $min_total_en;
        $coupon->min_total_cn = $min_total_cn;
        $coupon->min_total_vi = $min_total_vi;

        return $coupon;
    }
}
