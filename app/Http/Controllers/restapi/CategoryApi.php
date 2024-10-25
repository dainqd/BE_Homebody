<?php

namespace App\Http\Controllers\restapi;

use App\Enums\CategoryStatus;
use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Properties;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CategoryApi extends Api
{
    /**
     * @OA\Get(
     *     path="/api/categories/list",
     *     tags={"Category"},
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
    public function list()
    {
        try {
            $categories = Categories::where('status', '=', CategoryStatus::ACTIVE)
                ->where('parent_id', null)
                ->orderBy('id', 'desc')
                ->cursor()
                ->map(function ($item) {
                    $category = $item->toArray();

                    $child = Categories::where('parent_id', $item->id)
                        ->orderBy('id', 'DESC')
                        ->get();

                    $category['child'] = $child->toArray();
                    return $category;
                });

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
     *     path="/api/categories/detail/{id}",
     *     tags={"Category"},
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
    public function detail($id)
    {
        try {
            $category = Categories::find($id);
            if (!$category || $category->status != CategoryStatus::ACTIVE) {
                $data = returnMessage(-1, 404, null, 'Category not found!');
                return response()->json($data, 404);
            }

            $child = Categories::where('parent_id', $category->id)
                ->orderBy('id', 'DESC')
                ->get();

            $category = $category->toArray();

            $category['child'] = $child->toArray();

            $data = returnMessage(1, 200, $category, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
