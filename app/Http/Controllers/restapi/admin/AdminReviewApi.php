<?php

namespace App\Http\Controllers\restapi\admin;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Api;
use App\Models\Reviews;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminReviewApi extends Api
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * @OA\Get(
     *     path="/api/admin/reviews/list",
     *     summary="Get list of reviews",
     *     description="Get list of reviews",
     *     tags={"Admin Review"},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized user"
     *     )
     * )
     */
    public function list(Request $request)
    {
        try {
            $reviews = Reviews::where('reviews.status', '!=', ReviewStatus::DELETED)
                ->join('users', 'reviews.user_id', '=', 'users.id')
                ->orderBy('reviews.id', 'desc')
                ->select('reviews.*', 'users.email as email', 'users.phone as phone')
                ->get();
            $data = returnMessage(1, 200, $reviews, 'Success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function detail($id, Request $request)
    {
        try {
            $review = Reviews::where('id', $id)
                ->where('status', '!=', ReviewStatus::DELETED)
                ->first();
            if ($review == null) {
                $data = returnMessage(-1, 400, null, 'Review not found!');
                return response()->json($data, 404);
            }

            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $status = $request->input('status');
            $review = Reviews::where('id', $id)
                ->where('status', '!=', ReviewStatus::DELETED)
                ->first();
            if ($review == null) {
                $data = returnMessage(-1, 400, null, 'Review not found!');
                return response()->json($data, 404);
            }

            $review->status = $status ?? ReviewStatus::APPROVED;
            $review->save();

            $data = returnMessage(1, 200, $review, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function delete($id, Request $request)
    {
        try {
            $review = Reviews::where('id', $id)
                ->where('status', '!=', ReviewStatus::DELETED)
                ->first();

            if (!$review) {
                $data = returnMessage(-1, 400, '', 'Review not found!');
                return response($data, 400);
            }

            $review->status = ReviewStatus::DELETED;
            $review->save();

            $data = returnMessage(1, 200, $review, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

}
