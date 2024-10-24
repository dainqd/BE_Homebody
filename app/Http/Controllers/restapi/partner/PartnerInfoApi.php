<?php

namespace App\Http\Controllers\restapi\partner;

use App\Enums\PartnerInformationStatus;
use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PartnerInformations;
use Illuminate\Http\Request;
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

            $thumbnail = $request->input('thumbnail');
            $gallery = $request->input('gallery');

            $about = $request->input('about');
            $experience = $request->input('experience');
            $status = $request->input('status');

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
            $partner_->thumbnail = $thumbnail;
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

            $data = returnMessage(1, 200, '', 'Save information successfully!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
