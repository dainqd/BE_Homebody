<?php

namespace App\Http\Controllers\restapi;

use App\Enums\PartnerRegisterStatus;
use App\Http\Controllers\Controller;
use App\Models\PartnerRegister;
use App\Models\User;
use Illuminate\Http\Request;
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
     *                     example="123 456 7890"
     *                 )
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

            $partner = PartnerRegister::where('email', $email)->orWhere('phone', $phone)->first();
            if ($partner) {
                $data = returnMessage(1, '', 'Request already exists, please wait for us to verify');
                return response($data, 201);
            }

            $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$isEmail) {
                $data = returnMessage(-1, '', 'Email invalid!');
                return response($data, 400);
            }

            $is_valid = User::checkEmail($email);
            if (!$is_valid) {
                $data = returnMessage(-1, '', 'Email already exited!');
                return response($data, 400);
            }

            $is_valid = User::checkPhone($phone);
            if (!$is_valid) {
                $data = returnMessage(-1, '', 'Phone already exited!');
                return response($data, 400);
            }

            $partner = new PartnerRegister();
            $partner->name = $name;
            $partner->email = $email;
            $partner->phone = $phone;
            $partner->status = PartnerRegisterStatus::PENDING;
            $partner->save();

            $data = returnMessage(1, $partner, 'The request has been processed, please wait for us to verify the information.');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
