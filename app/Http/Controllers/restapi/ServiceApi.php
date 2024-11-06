<?php

namespace App\Http\Controllers\restapi;

use App\Enums\ServiceStatus;
use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ServiceApi extends Api
{
    /**
     * @OA\Get(
     *     path="/api/services/list",
     *     summary="List all services",
     *     description="List all services",
     *     operationId="2a2d7d6c2f0a09c4eaae9b4d1b0bd9cd",
     *     tags={"Service"},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="query",
     *         name="user_id",
     *         required=true,
     *         example="1",
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
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function list(Request $request)
    {
        try {
            $userID = $request->input('user_id');

            $services = Services::where('user_id', $userID)
                ->where('status', ServiceStatus::ACTIVE)
                ->orderBy('id', 'desc')
                ->get();
            $data = returnMessage(1, 200, $services, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Get detail of a service
     *
     * @OA\Get(
     *     path="/api/services/detail/{id}",
     *     tags={"Service"},
     *     summary="Get detail of a service",
     *     description="Get detail of a service",
     *     @OA\Parameter(
     *         description="Service ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
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
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function detail($id)
    {
        try {
            $service = Services::find($id);

            if (!$service || $service->status != ServiceStatus::ACTIVE) {
                $data = returnMessage(-1, 400, null, 'Service not found');
                return response($data, 400);
            }

            $data = returnMessage(1, 200, $service, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
