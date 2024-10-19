<?php

namespace App\Http\Controllers\restapi;

use App\Enums\PartnerRegisterStatus;
use App\Http\Controllers\Controller;
use App\Models\PartnerRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;

class PartnerRegisterApi extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/partner/register",
     *     summary="Send a request to become a partner",
     *     description="Send a request to become a partner",
     *     tags={"Partner"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name of the partner",
     *                     example="John Doe"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email of the partner",
     *                     example="john@example.com"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Phone of the partner",
     *                     example="1234567890"
     *                 ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="Password of the partner",
     *                      example="123456"
     *                  ),
     *                  @OA\Property(
     *                      property="password_confirm",
     *                      type="string",
     *                      description="Password confirmation of the partner",
     *                      example="123456"
     *                  )
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

            $data = returnMessage(1, 200, $partner, 'The request has been processed, please wait for us to verify the information.');
            return response($data, 400);
        } catch (\Exception $exception) {
            $data = returnMessage(1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
