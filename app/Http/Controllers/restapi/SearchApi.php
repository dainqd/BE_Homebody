<?php

namespace App\Http\Controllers\restapi;

use App\Enums\UserStatus;
use App\Http\Controllers\Api;
use App\Models\PartnerInformations;
use App\Models\Services;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SearchApi extends Api
{

    /**
     * @OA\Get(
     *     path="/api/search/user",
     *     summary="Search user by keyword, price range, and location",
     *     tags={"Search"},
     *     @OA\Parameter(
     *         description="Keyword to search",
     *         in="query",
     *         name="keyword",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Minimum price",
     *         in="query",
     *         name="minPrice",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Maximum price",
     *         in="query",
     *         name="maxPrice",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Province ID",
     *         in="query",
     *         name="province_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="District ID",
     *         in="query",
     *         name="district_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Commune ID",
     *         in="query",
     *         name="commune_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *          @OA\Parameter(
     *          description="size",
     *          in="query",
     *          name="size",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *          @OA\Parameter(
     *          description="sort",
     *          in="query",
     *          name="sort",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *          @OA\Parameter(
     *          description="page",
     *          in="query",
     *          name="page",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
    public function searchUser(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            $category = $request->input('category_id');
            $minPrice = $request->input('minPrice') ?? 0;
            $maxPrice = $request->input('maxPrice') ?? 100000000;
            $province_id = $request->input('province_id');
            $district_id = $request->input('district_id');
            $commune_id = $request->input('commune_id');
            $size = $request->input('size') ?? 10;
            $sort = $request->input('sort') ?? 'asc';
            $page = $request->input('page') ?? 1;

            $data = User::where('users.status', UserStatus::ACTIVE)
                ->leftJoin('partner_informations', 'users.id', '=', 'partner_informations.user_id')
                ->leftJoin('services', 'users.id', '=', 'services.user_id')
                ->leftJoin('categories', 'categories.id', '=', 'services.category_id');

            if ($keyword) {
                $data->where(function ($query) use ($keyword) {
                    $query->orWhere('partner_informations.name', 'like', "%$keyword%")
                        ->orWhere('users.full_name', 'like', "%$keyword%")
                        ->orWhere('partner_informations.phone', 'like', "%$keyword%")
                        ->orWhere('users.phone', 'like', "%$keyword%")
                        ->orWhere('partner_informations.email', 'like', "%$keyword%")
                        ->orWhere('users.email', 'like', "%$keyword%")
                        ->orWhere('services.name_en', 'like', "%$keyword%")
                        ->orWhere('services.name_cn', 'like', "%$keyword%")
                        ->orWhere('services.name_vi', 'like', "%$keyword%")
                        ->orWhere('categories.name_cn', 'like', "%$keyword%")
                        ->orWhere('categories.name_en', 'like', "%$keyword%")
                        ->orWhere('categories.name_vi', 'like', "%$keyword%");
                });
            }

            if ($category) {
                $data->where('services.category_id', '=', $category);
            }

            $data->whereBetween('services.discount_price', [$minPrice, $maxPrice]);

            $locationFilters = [
                'partner_informations.province_id' => $province_id,
                'partner_informations.district_id' => $district_id,
                'partner_informations.commune_id' => $commune_id
            ];
            foreach ($locationFilters as $field => $value) {
                if ($value) {
                    $data->where($field, $value);
                }
            }

            $total = $data->distinct()->count('users.id');

            if ($sort) {
                $data->orderBy('services.discount_price', $sort);
            }

            $data->skip(($page - 1) * $size)->take($size);

//            \Log::info($data->toSql());

            $results = $data->distinct()
                ->select('users.*')
//                ->limit($size)
//                ->offset(($page - 1) * $size)
                ->get()
                ->map(function ($item) {
                    $result = $item->toArray();

                    $partnerInformation = PartnerInformations::where('user_id', $item->id)->first();
                    $result['partner_information'] = $partnerInformation ? $partnerInformation->toArray() : null;

                    $services = Services::where('user_id', $item->id)->get();
                    $result['services'] = $services->toArray();

                    return $result;
                });

            $rs = [
                'total' => $total,
                'size' => $size,
                'page' => $page,
                'data' => $results
            ];

            $data = returnMessage(1, 200, $rs, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            \Log::error('Error in data retrieval: ' . $exception->getMessage() . ' at line ' . $exception->getLine());
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
