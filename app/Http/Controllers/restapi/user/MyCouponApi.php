<?php

namespace App\Http\Controllers\restapi\user;

use App\Enums\CouponStatus;
use App\Enums\MyCouponStatus;
use App\Http\Controllers\Controller;
use App\Models\Coupons;
use App\Models\MyCoupons;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class MyCouponApi extends Controller
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
     *     path="/api/my-coupons/list",
     *     tags={"My Coupon"},
     *     summary="Get list of my coupons",
     *     description="Get list of my coupons",
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
            $coupons = MyCoupons::where('user_id', $this->user['id'])
                ->orderByDesc('id')
                ->cursor()
                ->map(function ($item) {
                    $mycoupon = $item->toArray();

                    $coupon = Coupons::where('id', $item->coupon_id)->first();
                    $mycoupon['coupon'] = $coupon->toArray();
                    return $mycoupon;
                });
            $data = returnMessage(1, 200, $coupons, 'Success');

            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Search for coupons by code or name
     *
     * @OA\Get(
     *     path="/api/my-coupons/search",
     *     tags={"My Coupon"},
     *     summary="Search for coupons by code or name",
     *     description="Search for coupons by code or name",
     *     @OA\Parameter(
     *         description="Code",
     *         in="query",
     *         name="code",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Name",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
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
    public function search(Request $request)
    {
        try {
            $code = $request->input('code');
            $name = $request->input('name');

            $coupons = MyCoupons::where('my_coupons.user_id', $this->user['id'])
                ->join('coupons', 'coupons.id', '=', 'my_coupons.coupon_id')
                ->where('my_coupons.status', MyCouponStatus::UNUSED)
                ->select('my_coupons.*', 'coupons.*') // Adjust selection as needed
                ->orderByDesc('my_coupons.id');

            if ($code) {
                $coupons->where('coupons.code', $code);
            }

            if ($name) {
                $coupons->where('coupons.name', 'like', "%$name%");
            }

            $coupons = $coupons->get();

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
     *     path="/api/my-coupons/detail/{id}",
     *     tags={"My Coupon"},
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
            $coupon = MyCoupons::find($id);

            if (!$coupon) {
                $data = returnMessage(-1, 404, '', 'No coupon found!');
                return response($data, 404);
            }

            $coupon = Coupons::where($coupon->coupon_id);

            if (!$coupon || $coupon->status != CouponStatus::ACTIVE) {
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
     * Save a coupon
     *
     * @OA\Post(
     *     path="/api/my-coupons/save",
     *     tags={"My Coupon"},
     *     summary="Save a coupon",
     *     description="Save a coupon",
     *     @OA\Parameter(
     *         description="Coupon ID",
     *         in="query",
     *         name="coupon_id",
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
    public function saveCoupon(Request $request)
    {
        try {
            $user_id = $this->user['id'];
            $coupon_id = $request->input('coupon_id');
            $status = MyCouponStatus::UNUSED;

            $coupon = Coupons::find($coupon_id);

            if (!$coupon || $coupon->status != CouponStatus::ACTIVE) {
                $data = returnMessage(-1, 404, '', 'No coupon found!');
                return response($data, 404);
            }

            $count = MyCoupons::where('coupon_id', $coupon_id)->where('user_id', $user_id)->count();

            if ($count >= $coupon->max_set) {
                $data = returnMessage(-1, 400, '', 'Maximum storage limit reached!');
                return response($data, 400);
            }

            $myCoupon = new MyCoupons();

            $myCoupon->coupon_id = $coupon_id;
            $myCoupon->user_id = $user_id;
            $myCoupon->status = $status;

            $myCoupon->save();

            $data = returnMessage(1, 200, $myCoupon, 'Success');
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
     *     path="/api/my-coupons/delete/{id}",
     *     tags={"My Coupon"},
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
     *     )
     * )
     */
    public function delete($id)
    {
        try {
            $coupon = MyCoupons::find($id);

            if (!$coupon || $coupon->status != CouponStatus::ACTIVE) {
                $data = returnMessage(-1, 404, '', 'No coupon found!');
                return response($data, 404);
            }

            $coupon?->delete();

            $data = returnMessage(1, 200, 'Delete success!', 'Delete success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
