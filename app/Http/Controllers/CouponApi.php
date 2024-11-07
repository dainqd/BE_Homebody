<?php

namespace App\Http\Controllers;

use App\Enums\CouponStatus;
use App\Models\Coupons;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CouponApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coupons/list",
     *     tags={"Coupon"},
     *     summary="Get list of all active coupons",
     *     description="Get list of all active coupons",
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
            $coupons = Coupons::where('status', CouponStatus::ACTIVE)
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
     * Search coupon by code or name
     *
     * @OA\Get(
     *     path="/api/coupons/search",
     *     tags={"Coupon"},
     *     summary="Search coupon by code or name",
     *     description="Search coupon by code or name",
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
     *         response=404,
     *         description="No coupon found!"
     *     )
     * )
     */
    public function search(Request $request)
    {
        try {
            $code = $request->input('code');
            $name = $request->input('name');

            $coupons = Coupons::where('status', CouponStatus::ACTIVE)
                ->orderByDesc('id');

            if ($code) {
                $coupons->where('code', $code);
            }

            if ($name) {
                $coupons->where('name', 'like', "%$name%");
            }

            $coupons->get();

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
     *     path="/api/coupons/detail/{id}",
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
}
