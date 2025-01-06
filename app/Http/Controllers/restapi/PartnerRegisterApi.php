<?php

namespace App\Http\Controllers\restapi;

use App\Enums\PartnerInformationStatus;
use App\Enums\PartnerRegisterStatus;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\PartnerInformations;
use App\Models\PartnerRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PartnerRegisterApi extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/partner/register",
     *     summary="Send a request to become a partner",
     *     description="Send a request to become a partner",
     *     tags={"Partner"},
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
     *                 @OA\Property(property="specialty", type="string", example="Dentist"),
     *                 @OA\Property(property="password",type="string",description="Password of the partner",example="123456"),
     *                 @OA\Property(property="password_confirm",type="string",description="Password confirmation of the partner",example="123456")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Partner request sent successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function create(Request $request)
    {
        try {
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $password_confirm = $request->input('password_confirm');

            $partner = PartnerRegister::where('email', $email)->orWhere('phone', $phone)->first();
            if ($partner) {
                $data = returnMessage(1, 400, '', 'Request already exists, please wait for us to verify');
                return response($data, 400);
            }

            $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$isEmail) {
                $data = returnMessage(1, 400, '', 'Email invalid!');
                return response($data, 400);
            }

            $is_valid = User::checkEmail($email);
            if (!$is_valid) {
                $data = returnMessage(1, 400, '', 'Email already exited!');
                return response($data, 400);
            }

            $is_valid = User::checkPhone($phone);
            if (!$is_valid) {
                $data = returnMessage(1, 400, '', 'Phone already exited!');
                return response($data, 400);
            }

            if ($password != $password_confirm) {
                $data = returnMessage(1, 400, '', 'Password or Password Confirm incorrect!');
                return response($data, 400);
            }

            if (strlen($password) < 5) {
                $data = returnMessage(1, 400, '', 'Password invalid!');
                return response($data, 400);
            }

            $partner = new PartnerRegister();
            $partner->name = $name;
            $partner->email = $email;
            $partner->phone = $phone;
            $partner->password = $password;
            $partner->confirm_password = $password_confirm;
            $partner->status = PartnerRegisterStatus::PENDING;
            $partner->save();

            $data = [
                'email' => $email
            ];

            Mail::send('layouts.email.join-partner', $data, function ($message) use ($email) {
                $message->to($email, 'Partner registration notification email')->subject('Partner registration notification email');
                $message->from('devfullstack@gmail.com', 'Partner registration');
            });

            $user = $this->createUser($partner);

            $this->updateInfo($request, $user->id);

            $data = returnMessage(1, 200, $partner, 'The request has been processed, please wait for us to verify the information.');
            return response($data, 400);
        } catch (\Exception $exception) {
            $data = returnMessage(1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    private function createUser(PartnerRegister $partner)
    {
        $user = new User();

        $user->email = $partner->email;
        $user->status = UserStatus::INACTIVE;
        $user->phone = $partner->phone;
        $user->password = Hash::make($partner->password ?? '123456');
        $user->full_name = $partner->name;

        $user->save();

        (new MainController())->saveRolePartner($user->id);

        return $user;
    }

    private function updateInfo(Request $request, $userID)
    {
        $partner_ = new PartnerInformations();

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

        $gallery = '';
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
    }
}
