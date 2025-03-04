<?php

namespace App\Http\Controllers\restapi\admin;

use App\Enums\PartnerRegisterStatus;
use App\Enums\UserStatus;
use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\PartnerRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminPartnerRegisterApi extends Api
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
     *     path="/api/admin/partner-register/list",
     *     summary="Get all partner registers",
     *     description="Get all partner registers",
     *     tags={"Admin Partner Register"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="john@example.com"
     *                     ),
     *                     @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                         example="123 456 7890"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2022-01-01 00:00:00"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function list(Request $request)
    {
        $partners = PartnerRegister::where('status', '!=', PartnerRegisterStatus::DELETED)
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, 200, $partners, "Success");
        return response($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/partner-register/detail/{id}",
     *     summary="Get partner detail by id",
     *     description="Get partner detail by id",
     *     operationId="getPartnerDetailById",
     *     tags={"Admin Partner Register"},
     *     @OA\Parameter(
     *         description="ID of partner",
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
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="123 456 7890"),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2022-01-01 00:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partner not found"
     *     )
     * )
     */
    public function detail($id)
    {
        $partner = PartnerRegister::find($id);
        if (!$partner || $partner->status == PartnerRegisterStatus::DELETED) {
            $data = returnMessage(-1, 400, "Partner not found", null);
            return response($data, 404);
        }
        $data = returnMessage(1, 200, $partner, "Success");
        return response($data, 200);
    }

    /**
     * Update a partner register status.
     *
     * @OA\Put(
     *     path="/api/admin/partner-register/update/{id}",
     *     summary="Update a partner register status",
     *     description="Update a partner register status",
     *     operationId="updatePartnerRegisterStatus",
     *     tags={"Admin Partner Register"},
     *     @OA\Parameter(
     *         description="ID of partner",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Partner register status to be updated",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"APPROVED", "REJECTED", "DELETED"},
     *                 description="Status of the partner register",
     *                 example="APPROVED"
     *             ),
     *             @OA\Property(
     *                 property="update",
     *                 type="string",
     *                 enum={"Y", "N"},
     *                 description="Update the partner register status",
     *                 example="Y"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Partner register status updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partner register not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $status = $request->input('status');
            $is_update = $request->input('update');
            $partner = PartnerRegister::find($id);
            if (!$partner || $partner->status == PartnerRegisterStatus::DELETED) {
                $data = returnMessage(-1, 400, "Partner not found", null);
                return response($data, 404);
            }

            if ($is_update === 'Y') {
                $status = PartnerRegisterStatus::APPROVED;
            }

            if ($partner->status != PartnerRegisterStatus::PENDING) {
                $data = returnMessage(-1, 400, "Partner already approved or rejected", 'Cannot update partner already approved or rejected!');
                return response($data, 400);
            }

            $partner->status = $status;
            $partner->save();

            $user = User::where('email', $partner->email)->first();
            if ($user) {
                $user->status = UserStatus::ACTIVE;
                $user->save();
            }

            $data = returnMessage(1, 200, $partner, 'Update success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Delete a partner register by ID
     *
     * @OA\Delete(
     *     path="/api/admin/partner-register/delete/{id}",
     *     summary="Delete a partner register by ID",
     *     description="Delete a partner register by ID",
     *     tags={"Admin Partner Register"},
     *     @OA\Parameter(
     *         description="ID of partner",
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
     *         description="Partner register deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partner register not found"
     *     )
     * )
     */
    public function delete($id)
    {
        try {
            $partner = PartnerRegister::find($id);
            if (!$partner || $partner->status == PartnerRegisterStatus::DELETED) {
                $data = returnMessage(-1, 400, "Partner not found", null);
                return response($data, 404);
            }

            $partner->status = PartnerRegisterStatus::DELETED;
            $partner->save();
            $data = returnMessage(1, 200, $partner, 'Delete success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
