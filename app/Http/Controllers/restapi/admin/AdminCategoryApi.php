<?php

namespace App\Http\Controllers\restapi\admin;

use App\Enums\CategoryStatus;
use App\Enums\ServiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminCategoryApi extends Controller
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
     *     path="/api/admin/categories/list",
     *     tags={"Admin Category"},
     *     summary="Get list of categories",
     *     description="Get list of categories",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized user"
     *     ),
     * )
     */
    public function list(Request $request)
    {
        try {
            $categories = Categories::where('status', '!=', CategoryStatus::DELETED)
                ->orderBy('id', 'desc')
                ->get();
            $data = returnMessage(1, 200, $categories, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Get detail of a category
     *
     * @OA\Get(
     *     path="/api/admin/categories/detail/{id}",
     *     tags={"Admin Category"},
     *     summary="Get detail of a category",
     *     description="Get detail of a category",
     *     @OA\Parameter(
     *         description="Category ID",
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
     *         description="Category not found"
     *     )
     * )
     */
    public function detail(Request $request, $id)
    {
        try {
            $category = Categories::find($id);
            if (!$category || $category->status == CategoryStatus::DELETED) {
                $data = returnMessage(-1, 404, null, 'Category not found!');
                return response()->json($data, 404);
            }

            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Create a category
     *
     * @OA\Post(
     *     path="/api/admin/categories/create",
     *     tags={"Admin Category"},
     *     summary="Create category",
     *     description="Create category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name of the category"
     *                 ),
     *                 @OA\Property(
     *                     property="parent_id",
     *                     type="integer",
     *                     description="Parent category ID"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     enum={1, 2, 3},
     *                     description="Status of the category"
     *                 ),
     *                 @OA\Property(
     *                     property="thumbnail",
     *                     type="string",
     *                     format="binary",
     *                     description="Thumbnail of the category"
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
     *     )
     * )
     */
    public function create(Request $request)
    {
        try {
            $name = $request->input('name');

            $old_category = Categories::where('name', $name)
                ->where('status', '!=', CategoryStatus::DELETED)
                ->first();

            if ($old_category) {
                $data = returnMessage(-1, 400, null, 'Error, Category has created!');
                return response()->json($data, 400);
            }

            $category = new Categories();

            $category->name = $name;

            $category->name_en = language_helper($name, 'en');
            $category->name_cn = language_helper($name, 'zh-CN');
            $category->name_vi = language_helper($name, 'vi');

            $parent_id = $request->input('parent_id');

            if ($parent_id && $parent_id != "") {
                $category->parent_id = $parent_id;
            } else {
                $category->parent_id = null;
            }

            if ($request->hasFile('thumbnail')) {
                $item = $request->file('thumbnail');
                $itemPath = $item->store('category', 'public');
                $thumbnail = asset('storage/' . $itemPath);
                $category->thumbnail = $thumbnail;
            } else {
                $data = returnMessage(-1, 400, null, 'Error, Please upload thumbnail!');
                return response()->json($data, 400);
            }

            $category->status = $request->input('status');
            $category->created_by = $this->user['id'];

            $success = $category->save();

            if ($success) {
                $data = returnMessage(1, 200, $category, 'Success, Create category successful!');
                return response()->json($data, 200);
            }

            $data = returnMessage(-1, 400, null, 'Error, Create error!');
            return response()->json($data, 400);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/categories/update/{id}",
     *     summary="Update a category",
     *     description="Update a category",
     *     tags={"Admin Category"},
     *     @OA\Parameter(
     *         description="Category ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     * *         required=true,
     * *         @OA\MediaType(
     * *             mediaType="multipart/form-data",
     * *             @OA\Schema(
     * *                 @OA\Property(
     * *                     property="name",
     * *                     type="string",
     * *                     description="Name of the category"
     * *                 ),
     * *                 @OA\Property(
     * *                     property="parent_id",
     * *                     type="integer",
     * *                     description="Parent category ID"
     * *                 ),
     * *                 @OA\Property(
     * *                     property="status",
     * *                     type="integer",
     * *                     enum={1, 2, 3},
     * *                     description="Status of the category"
     * *                 ),
     * *                 @OA\Property(
     * *                     property="thumbnail",
     * *                     type="string",
     * *                     format="binary",
     * *                     description="Thumbnail of the category"
     * *                 )
     * *             )
     * *         )
     * *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Categories::find($id);

            $name = $request->input('name');

            if ($name != $category->name) {
                $old_category = Categories::where('name', $name)
                    ->where('status', '!=', CategoryStatus::DELETED)
                    ->first();

                if ($old_category) {
                    $data = returnMessage(-1, 400, null, 'Error, Category has created!');
                    return response()->json($data, 400);
                }

                $category->name = $name;

                $category->name_en = language_helper($name, 'en');
                $category->name_cn = language_helper($name, 'zh-CN');
                $category->name_vi = language_helper($name, 'vi');
            }

            $parent_id = $request->input('parent_id');

            if ($parent_id && $parent_id !== "") {
                $category->parent_id = $parent_id;
            }

            if ($request->hasFile('thumbnail')) {
                $item = $request->file('thumbnail');
                $itemPath = $item->store('category', 'public');
                $thumbnail = asset('storage/' . $itemPath);
                $category->thumbnail = $thumbnail;
            }

            $category->status = $request->input('status');
            $category->updated_by = $this->user['id'];
            $category->updated_at = Carbon::now()->addHours(7); /* GMT +7*/

            $success = $category->save();

            if ($success) {
                $data = returnMessage(2, 200, null, 'Success, Update category successful!');
                return response()->json($data, 200);
            }

            $data = returnMessage(-1, 400, null, 'Error, Update error!');
            return response()->json($data, 400);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Delete a category
     *
     * @OA\Delete(
     *     path="/admin/categories/{id}",
     *     tags={"Admin Category"},
     *     summary="Delete a category",
     *     description="Delete a category",
     *     @OA\Parameter(
     *         description="Category ID",
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
     *     )
     * )
     */
    public function delete(Request $request, $id)
    {
        try {
            $category = Categories::find($id);

            $products = Services::where('category_id', $id)
                ->where('status', '!=', ServiceStatus::DELETED)
                ->get();

            if (count($products) > 0) {
                $data = returnMessage(-1, 400, null, 'Cannot delete category with products!');
                return response()->json($data, 400);
            }

            $category->status = CategoryStatus::DELETED;
            $category->deleted_by = $this->user['id'];
            $category->deleted_at = Carbon::now()->addHours(7);

            $success = $category->save();

            if ($success) {
                $data = returnMessage(1, 200, $category, 'Success, Delete category successful!');
                return response()->json($data, 200);
            }

            $data = returnMessage(-1, 400, null, 'Delete, Create error!');
            return response()->json($data, 400);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    private function save(Request $request)
    {

    }
}
