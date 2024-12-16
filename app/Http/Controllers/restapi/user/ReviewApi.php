<?php

namespace App\Http\Controllers\restapi\user;

use App\Enums\BookingStatus;
use App\Enums\ReviewStatus;
use App\Http\Controllers\Api;
use App\Models\Booking;
use App\Models\Reviews;
use App\Models\Services;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewApi extends Api
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/list",
     *     summary="Get list of reviews",
     *     description="Get list of reviews",
     *     tags={"Review"},
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
            $partner_id = $request->input('partner_id');
            $reviews = Reviews::where('status', ReviewStatus::APPROVED);
            $reviews->where('partner_id', $partner_id);

            $reviews = $reviews->orderBy('id', 'desc')
                ->cursor()
                ->map(function ($item) {
                    $review = $item->toArray();

                    $user = User::find($item->user_id);

                    $review['user'] = $user->toArray();

                    return $review;
                });

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

    public function create(Request $request)
    {
        try {
            $user = $this->user;
            $booking_id = $request->input('booking_id');
            $comment = $request->input('comment');
            $rating = $request->input('rating');

            $service_id = $request->input('service_id');

            $review = new Reviews();
            $review->user_id = $user['id'];
            $review->booking_id = $booking_id;
            $review->rating = $rating;
            $review->comment = $comment;

            $booking = Booking::find($booking_id);

            if (!$booking) {
                $data = returnMessage(-1, 400, '', 'Booking not found!');
                return response($data, 400);
            }

            if ($booking != BookingStatus::COMPLETED) {
                $data = returnMessage(-1, 400, '', 'Booking not completed!');
                return response($data, 400);
            }

            $service = Services::find($service_id);
            if (!$service) {
                $data = returnMessage(-1, 400, '', 'Service not found!');
                return response($data, 400);
            }

            $partner_id = $service->user_id;
            $review->partner_id = $partner_id;

            $gallery = '';
            if ($request->hasFile('thumbnail')) {
                $galleryPaths = array_map(function ($image) {
                    $itemPath = $image->store('reviews', 'public');
                    return asset('storage/' . $itemPath);
                }, $request->file('thumbnail'));
                $gallery = implode(',', $galleryPaths);
            }
            $review->file = $gallery;

            $review->status = ReviewStatus::PENDING;
            $review->service_id = $service_id;

            $review->save();

            $data = returnMessage(1, 200, $review, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $user = $this->user;

            $comment = $request->input('comment');
            $rating = $request->input('rating');

            $review = Reviews::where('id', $id)
                ->where('status', '!=', ReviewStatus::DELETED)
                ->where('user_id', $user['id'])
                ->first();

            if (!$review) {
                $data = returnMessage(-1, 400, '', 'Review not found!');
                return response($data, 400);
            }

            $review->rating = $rating;
            $review->comment = $comment;

            $gallery = '';
            if ($request->hasFile('thumbnail')) {
                $galleryPaths = array_map(function ($image) {
                    $itemPath = $image->store('reviews', 'public');
                    return asset('storage/' . $itemPath);
                }, $request->file('thumbnail'));
                $gallery = implode(',', $galleryPaths);
            }
            $review->file = $gallery;

            $review->save();

            $data = returnMessage(1, 200, $review, 'Success!');
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
