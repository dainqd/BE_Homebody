<?php

namespace App\Http\Controllers\restapi\partner;

use App\Enums\ServiceStatus;
use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PartnerInformations;
use App\Models\Services;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class PartnerServiceApi extends Api
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
     * List all services
     *
     * @OA\Get(
     *     path="/api/partner/services/list",
     *     tags={"Partner Service"},
     *     summary="List all services",
     *     description="List all services",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Service not found"
     *     )
     * )
     */
    public function list(Request $request)
    {
        try {
            $userID = $this->user['id'];

            $services = Services::where('user_id', $userID)
                ->orderBy('id', 'desc')
                ->where('status', '!=', ServiceStatus::DELETED)
                ->get();
            $data = returnMessage(1, 200, $services, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }


    /**
     * Create a new service
     *
     * @OA\Post(
     *     path="/api/partner/services/create",
     *     tags={"Partner Service"},
     *     summary="Create a new service",
     *     description="Create a new service",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name of the service",
     *                     example="Service name"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the service",
     *                     example="Service description"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="integer",
     *                     description="Price of the service",
     *                     example=10000
     *                 ),
     *                 @OA\Property(
     *                     property="discount_price",
     *                     type="integer",
     *                     description="Discount price of the service",
     *                     example=8000
     *                 ),
     *                 @OA\Property(
     *                     property="time_execution",
     *                     type="integer",
     *                     description="Time execution of the service",
     *                     example=60
     *                 ),
     *                 @OA\Property(
     *                     property="display_priority",
     *                     type="integer",
     *                     description="Display priority of the service",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="usage_conditions",
     *                     type="string",
     *                     description="Usage conditions of the service",
     *                     example="Service usage conditions"
     *                 ),
     *                 @OA\Property(
     *                     property="number_of_sessions",
     *                     type="integer",
     *                     description="Number of sessions of the service",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="promotion_start",
     *                     type="string",
     *                     format="date-time",
     *                     description="Promotion start time of the service",
     *                     example="2022-01-01 00:00:00"
     *                 ),
     *                 @OA\Property(
     *                     property="promotion_end",
     *                     type="string",
     *                     format="date-time",
     *                     description="Promotion end time of the service",
     *                     example="2022-01-31 23:59:59"
     *                 ),
     *                 @OA\Property(
     *                     property="category_id",
     *                     type="integer",
     *                     description="Category ID of the service",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="thumbnail",
     *                     type="file",
     *                     description="Thumbnail of the service",
     *                     example="https://example.com/thumbnail.jpg"
     *                 ),
     *                 @OA\Property(
     *                     property="gallery",
     *                     type="array",
     *                     @OA\Items(
     *                         type="file",
     *                         description="Gallery of the service",
     *                         example="https://example.com/gallery1.jpg"
     *                     ),
     *                     description="Gallery of the service"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Service not found"
     *     )
     * )
     */
    public function create(Request $request)
    {
        try {
            $service = new Services();

            $user_id = $this->user['id'];

            $info = PartnerInformations::where('user_id', $user_id)->first();
            if (!$info) {
                $data = returnMessage(-1, 400, null, 'Please update your information first');
                return response($data, 400);
            }

            $service = $this->save($request, $service);

            $service->save();

            $data = returnMessage(1, 200, $service, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }


    /**
     * Update a service by ID
     *
     * @OA\Put(
     *     path="/api/partner/services/update/{id}",
     *     tags={"Partner Service"},
     *     summary="Update a service by ID",
     *     description="Update a service by ID",
     *     @OA\Parameter(
     *         description="Service ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name of the service",
     *                     example="Service name"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the service",
     *                     example="Service description"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="integer",
     *                     description="Price of the service",
     *                     example=10000
     *                 ),
     *                 @OA\Property(
     *                     property="discount_price",
     *                     type="integer",
     *                     description="Discount price of the service",
     *                     example=8000
     *                 ),
     *                 @OA\Property(
     *                     property="time_execution",
     *                     type="integer",
     *                     description="Time execution of the service",
     *                     example=60
     *                 ),
     *                 @OA\Property(
     *                     property="display_priority",
     *                     type="integer",
     *                     description="Display priority of the service",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="usage_conditions",
     *                     type="string",
     *                     description="Usage conditions of the service",
     *                     example="Service usage conditions"
     *                 ),
     *                 @OA\Property(
     *                     property="number_of_sessions",
     *                     type="integer",
     *                     description="Number of sessions of the service",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="promotion_start",
     *                     type="string",
     *                     format="date-time",
     *                     description="Promotion start time of the service",
     *                     example="2022-01-01 00:00:00"
     *                 ),
     *                 @OA\Property(
     *                     property="promotion_end",
     *                     type="string",
     *                     format="date-time",
     *                     description="Promotion end time of the service",
     *                     example="2022-01-31 23:59:59"
     *                 ),
     *                 @OA\Property(
     *                     property="category_id",
     *                     type="integer",
     *                     description="Category ID of the service",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="thumbnail",
     *                     type="file",
     *                     description="Thumbnail of the service",
     *                     example="https://example.com/thumbnail.jpg"
     *                 ),
     *                 @OA\Property(
     *                     property="gallery",
     *                     type="array",
     *                     @OA\Items(
     *                         type="file",
     *                         description="Gallery of the service",
     *                         example="https://example.com/gallery1.jpg"
     *                     ),
     *                     description="Gallery of the service"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Service not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $service = Services::find($id);

            if (!$service || $service->status == ServiceStatus::DELETED) {
                $data = returnMessage(-1, 400, null, 'Service not found');
                return response($data, 400);
            }

            if ($service->user_id != $this->user['id']) {
                $data = returnMessage(-1, 400, null, 'Unauthorized user');
                return response($data, 400);
            }


            $service = $this->save($request, $service);

            $service->save();

            $data = returnMessage(1, 200, $service, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Delete a service by ID
     *
     * @OA\Delete(
     *     path="/api/partner/services/delete/{id}",
     *     tags={"Partner Service"},
     *     summary="Delete a service by ID",
     *     description="Delete a service by ID",
     *     @OA\Parameter(
     *         description="Service ID",
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
     *         response="401",
     *         description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Service not found"
     *     )
     * )
     */
    public function delete($id)
    {
        try {
            $service = Services::find($id);

            if (!$service || $service->status == ServiceStatus::DELETED) {
                $data = returnMessage(-1, 400, null, 'Service not found');
                return response($data, 400);
            }

            if ($service->user_id != $this->user['id']) {
                $data = returnMessage(-1, 400, null, 'Unauthorized user');
                return response($data, 400);
            }

            $service->status = ServiceStatus::DELETED;
            $service->save();

            $data = returnMessage(1, 200, $service, 'Success');
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
     *     path="/api/partner/services/detail/{id}",
     *     tags={"Partner Service"},
     *     summary="Get detail of a service",
     *     description="Get detail of a service",
     *     @OA\Parameter(
     *         description="Service ID",
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
     *         response="401",
     *         description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Service not found"
     *     )
     * )
     */
    public function detail($id)
    {
        try {
            $service = Services::find($id);

            if (!$service || $service->status == ServiceStatus::DELETED) {
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

    private function save(Request $request, Services $services)
    {
        $user_id = $this->user['id'];

        $name = $request->input('name');
        $name_en = language_helper($name, 'en');
        $name_cn = language_helper($name, 'zh-CN');
        $name_vi = language_helper($name, 'vi');

        $description = $request->input('description');
        $description_en = language_helper($description, 'en');
        $description_cn = language_helper($description, 'zh-CN');
        $description_vi = language_helper($description, 'vi');

        $price = $request->input('price');
        $price_en = $request->input('price_en');
        $price_cn = $request->input('price_cn');
        $price_vi = $request->input('price_vi');

        $discount_price = $request->input('discount_price');
        $discount_price_en = $request->input('discount_price_en');
        $discount_price_cn = $request->input('discount_price_cn');
        $discount_price_vi = $request->input('discount_price_vi');

        $time_execution = $request->input('time_execution');
        $display_priority = $request->input('display_priority');

        $usage_conditions = $request->input('usage_conditions');
        $usage_conditions_en = language_helper($usage_conditions, 'en');
        $usage_conditions_cn = language_helper($usage_conditions, 'zh-CN');
        $usage_conditions_vi = language_helper($usage_conditions, 'vi');

        $number_of_sessions = $request->input('number_of_sessions');
        $promotion_start = $request->input('promotion_start');
        $promotion_end = $request->input('promotion_end');

        $thumbnail = '';
        $gallery = '';
        if ($request->hasFile('thumbnail')) {
            $item = $request->file('thumbnail');
            $itemPath = $item->store('services', 'public');
            $thumbnail = asset('storage/' . $itemPath);
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = array_map(function ($image) {
                $itemPath = $image->store('services', 'public');
                return asset('storage/' . $itemPath);
            }, $request->file('gallery'));
            $gallery = implode(',', $galleryPaths);
        }

        $category_id = $request->input('category_id');

        $services->user_id = $user_id;
        $services->name = $name;
        $services->name_en = $name_en;
        $services->name_cn = $name_cn;
        $services->name_vi = $name_vi;
        $services->description = $description;
        $services->description_en = $description_en;
        $services->description_cn = $description_cn;
        $services->description_vi = $description_vi;
        $services->price = $price;
        $services->price_en = $price_en;
        $services->price_cn = $price_cn;
        $services->price_vi = $price_vi;
        $services->discount_price = $discount_price;
        $services->discount_price_en = $discount_price_en;
        $services->discount_price_cn = $discount_price_cn;
        $services->discount_price_vi = $discount_price_vi;
        $services->time_execution = $time_execution;
        $services->display_priority = $display_priority;
        $services->usage_conditions = $usage_conditions;
        $services->usage_conditions_en = $usage_conditions_en;
        $services->usage_conditions_cn = $usage_conditions_cn;
        $services->usage_conditions_vi = $usage_conditions_vi;
        $services->number_of_sessions = $number_of_sessions;
        $services->promotion_start = $promotion_start;
        $services->promotion_end = $promotion_end;
        $services->thumbnail = $thumbnail;
        $services->gallery = $gallery;
        $services->category_id = $category_id;

        return $services;
    }
}
