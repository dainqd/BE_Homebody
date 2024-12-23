<?php

namespace App\Http\Controllers\restapi\admin;

use App\Enums\CouponStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Coupons;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminCouponApi extends Controller
{
    protected $user;

    /**
     * Instantiate a new CheckoutController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate()->toArray();
    }

    /**
     * @OA\Get(
     *     path="/api/admin/coupons/list",
     *     tags={"Coupon"},
     *     summary="Get list of all coupons",
     *     description="Get list of all coupons",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Internal Server error"
     *     )
     * )
     */
    public function list()
    {
        try {
            $coupons = Coupons::where('status', '!=', CouponStatus::DELETED)
                ->orderByDesc('id')
                ->get();
            $data = returnMessage(1, 200, $coupons, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }


    /**
     * Search for coupons with status not equal to ACTIVE.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\Response The HTTP response containing the search results or error message.
     *
     * @throws \Exception If an error occurs during the search process.
     */
    public function search(Request $request)
    {
        try {
            $coupons = Coupons::where('status', '!=', CouponStatus::ACTIVE)
                ->orderByDesc('id')
                ->get();
            $data = returnMessage(1, 200, $coupons, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Get detail of a coupon
     *
     * @OA\Get(
     *     path="/api/admin/coupons/detail/{id}",
     *     tags={"Coupon"},
     *     summary="Get detail of a coupon",
     *     description="Get detail of a coupon",
     *     @OA\Parameter(
     *         description="Coupon ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No coupon found!"
     *     )
     * )
     */
    public function detail($id)
    {
        try {
            $coupon = Coupons::find($id);

            if (!$coupon || $coupon->status == CouponStatus::DELETED) {
                $data = returnMessage(-1, 404, '', 'No coupon found!');
                return response($data, 404);
            }

            $data = returnMessage(1, 200, $coupon, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/admin/coupons/create",
     *     tags={"Coupon"},
     *     summary="Create a new coupon",
     *     description="Create a new coupon",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="code",
     *                     description="Code",
     *                     type="string",
     *                     example="COUPON123"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="Name",
     *                     type="string",
     *                     example="My Coupon"
     *                 ),
     *                 @OA\Property(
     *                     property="max_set",
     *                     description="Maximum amount of coupon",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="start_date",
     *                     description="Start date",
     *                     type="string",
     *                     format="date",
     *                     example="2021-01-01"
     *                 ),
     *                 @OA\Property(
     *                     property="end_date",
     *                     description="End date",
     *                     type="string",
     *                     format="date",
     *                     example="2021-01-31"
     *                 ),
     *                 @OA\Property(
     *                     property="percent_discount",
     *                     description="Percent discount",
     *                     type="integer",
     *                     example=10
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     description="Status",
     *                     type="integer",
     *                     example=CouponStatus::ACTIVE
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Internal Server error"
     *     )
     * )
     */
    public function create(Request $request)
    {
        try {
            $coupon = new Coupons();

            $coupon = $this->save($request, $coupon);
            $coupon->save();

            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/coupons/update/{id}",
     *     tags={"Coupon"},
     *     summary="Update a coupon",
     *     description="Update a coupon",
     *     @OA\Parameter(
     *         description="Coupon ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="code",
     *                 description="Code",
     *                 type="string",
     *                 example="SUMMER15"
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 description="Name",
     *                 type="string",
     *                 example="Summer 15% off"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 description="Description",
     *                 type="string",
     *                 example="15% off on all orders over $100"
     *             ),
     *             @OA\Property(
     *                 property="percent_discount",
     *                 description="Percent discount",
     *                 type="integer",
     *                 example=15
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 description="Status",
     *                 type="integer",
     *                 example=CouponStatus::ACTIVE
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No coupon found!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Internal Server error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $coupon = Coupons::find($id);

            if (!$coupon || $coupon->status == CouponStatus::DELETED) {
                $data = returnMessage(-1, 404, '', 'Không tìm thấy phiếu giảm giá!');
                return response($data, 404);
            }

            $coupon = $this->save($request, $coupon);
            $coupon->save();

            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Delete a coupon
     *
     * @OA\Delete(
     *     path="/api/admin/coupons/delete/{id}",
     *     tags={"Coupon"},
     *     summary="Delete a coupon",
     *     description="Delete a coupon",
     *     @OA\Parameter(
     *         description="Coupon ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No coupon found!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Internal Server error"
     *     )
     * )
     */
    public function delete($id)
    {
        try {
            $coupon = Coupons::find($id);

            if (!$coupon || $coupon->status == CouponStatus::DELETED) {
                $data = returnMessage(-1, 404, '', 'Không tìm thấy phiếu giảm giá!');
                return response($data, 404);
            }

            $coupon->status = CouponStatus::DELETED;
            $coupon->save();

            $data = returnMessage(1, 200, $coupon, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
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

        $created_by = $this->user['id'];

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
