<?php

namespace App\Http\Controllers\restapi\partner;

use App\Enums\PartnerInformationStatus;
use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PartnerInformations;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class PartnerInfoApi extends Api
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
     * @OA\Post(
     *     path="/api/partner/update/info",
     *     summary="Save partner information",
     *     description="Save partner information",
     *     tags={"Partner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Partner information",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="phone", type="string", example="1234567890"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="gender", type="string", example="Male"),
     *                 @OA\Property(property="thumbnail", type="string", format="binary", description="Thumbnail image file"),
     *                 @OA\Property(
     *                     property="gallery",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary", description="Gallery image file")
     *                 ),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="longitude", type="string", example="37.7749"),
     *                 @OA\Property(property="latitude", type="string", example="-122.4194"),
     *                 @OA\Property(property="about", type="string", example="About me"),
     *                 @OA\Property(property="experience", type="string", example="Experience"),
     *                 @OA\Property(property="status", type="string", example="APPROVED"),
     *                 @OA\Property(property="province_id", type="integer", example=1),
     *                 @OA\Property(property="district_id", type="integer", example=1),
     *                 @OA\Property(property="commune_id", type="integer", example=1),
     *                 @OA\Property(property="tax_code", type="string", example="1234567890"),
     *                 @OA\Property(property="passport", type="string", format="binary", description="Passport image file"),
     *                 @OA\Property(property="time_working", type="string", example="8h-12h"),
     *                 @OA\Property(property="day_working", type="string", example="Monday"),
     *                 @OA\Property(property="specialty", type="string", example="Dentist")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Save information successfully!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function saveInfo(Request $request)
    {
        try {
            $userID = $this->user['id'];

            $partner_ = PartnerInformations::where('user_id', $userID)->first();

            if (!$partner_) {
                $partner_ = new PartnerInformations();
            }
            $name = $request->input('name');

            $phone = $request->input('phone');
            $email = $request->input('email');
            $gender = $request->input('gender');

            $about = $request->input('about');
            $experience = $request->input('experience');
            $status = PartnerInformationStatus::ACTIVE;

            $address = $request->input('address');

            $longitude = $request->input('longitude');
            $latitude = $request->input('latitude');

            $province_id = $request->input('province_id');
            $district_id = $request->input('district_id');
            $commune_id = $request->input('commune_id');

            $tax_code = $request->input('tax_code');
            $passport = $request->input('passport');
            $time_working = $request->input('time_working');
            $day_working = $request->input('day_working');

            $specialty = $request->input('specialty');

            $partner_->name = $name;
            $partner_->phone = $phone;
            $partner_->email = $email;
            $partner_->gender = $gender;

            if ($request->hasFile('thumbnail')) {
                $item = $request->file('thumbnail');
                $itemPath = $item->store('partner', 'public');
                $thumbnail = asset('storage/' . $itemPath);
                $partner_->thumbnail = $thumbnail;
            }

            if ($request->hasFile('gallery')) {
                $galleryPaths = array_map(function ($image) {
                    $itemPath = $image->store('partner', 'public');
                    return asset('storage/' . $itemPath);
                }, $request->file('gallery'));
                $gallery = implode(',', $galleryPaths);
            }

            $partner_->gallery = $gallery;
            $partner_->address = $address;
            $partner_->longitude = $longitude;
            $partner_->latitude = $latitude;
            $partner_->about = $about;
            $partner_->experience = $experience;
            $partner_->status = $status;
            $partner_->user_id = $userID;

            $partner_->province_id = $province_id;
            $partner_->district_id = $district_id;
            $partner_->commune_id = $commune_id;

            $partner_->tax_code = $tax_code;
            $partner_->passport = $passport;
            $partner_->time_working = $time_working;
            $partner_->day_working = $day_working;

            $partner_->specialty = $specialty;
            $partner_->specialty_en = language_helper($specialty, 'en');
            $partner_->specialty_cn = language_helper($specialty, 'zh-CN');
            $partner_->specialty_vi = language_helper($specialty, 'vi');

            $partner_->save();

            $data = returnMessage(1, 200, $partner_, 'Save information successfully!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
