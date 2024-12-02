<?php
//
//namespace App\Http\Controllers\restapi\user;
//
//use App\Http\Controllers\Api;
//use App\Http\Controllers\Controller;
//use App\Models\Carts;
//use App\Models\Services;
//use Illuminate\Http\Request;
//use OpenApi\Annotations as OA;
//use Tymon\JWTAuth\Facades\JWTAuth;
//
//class CartApi extends Api
//{
//    protected $user;
//
//    /**
//     * Instantiate a new CheckoutController instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->user = JWTAuth::parseToken()->authenticate();
//    }
//
//    /**
//     * Get list cart
//     *
//     * @OA\Get(
//     *     path="/api/carts/list",
//     *     summary="Get list cart",
//     *     tags={"Cart"},
//     *     @OA\Response(response=401, description="Unauthorized"),
//     *     @OA\Response(response=200, description="Success"),
//     *     @OA\Response(response=400, description="Error")
//     * )
//     */
//    public function list()
//    {
//        $user = $this->user->toArray();
//
//        $carts = Carts::where('user_id', $user['id'])
//            ->cursor()
//            ->map(function ($item) {
//                $cart = $item->toArray();
//                $service = Services::find($item->service_id);
//                $cart['services'] = $service->toArray();
//                return $cart;
//            });
//
//        $data = returnMessage(1, 200, $carts, 'Success');
//        return response($data, 200);
//    }
//
//
//    /**
//     * Add to cart
//     *
//     * @OA\Post(
//     *     path="/api/carts/add",
//     *     summary="Add to cart",
//     *     tags={"Cart"},
//     *     @OA\RequestBody(
//     *         @OA\JsonContent(
//     *             type="object",
//     *             @OA\Property(
//     *                 property="service_id",
//     *                 type="integer",
//     *                 example=1,
//     *                 description="Service ID"
//     *             ),
//     *             @OA\Property(
//     *                 property="quantity",
//     *                 type="integer",
//     *                 example=1,
//     *                 description="Quantity of product"
//     *             ),
//     *             @OA\Property(
//     *                 property="values",
//     *                 type="string",
//     *                 example="",
//     *                 description="Values of service"
//     *             )
//     *         )
//     *     ),
//     *     @OA\Response(response=400, description="Bad request"),
//     *     @OA\Response(response=401, description="Unauthorized"),
//     *     @OA\Response(response=404, description="Service not found!"),
//     *     @OA\Response(response=500, description="Internal Server Error")
//     * )
//     */
//    public function addToCart(Request $request)
//    {
//        try {
//            $user = $this->user->toArray();
//            $user_id = $user['id'];
//
//            $service_id = $request->input('service_id');
//            $quantity = $request->input('quantity') ?? 1;
//            $values = $request->input('values') ?? '';
//
//            if ($quantity < 1) {
//                $data = returnMessage(-1, 400, '', 'Quantity must be greater than 0!');
//                return response($data, 400);
//            }
//
//            $service = Services::find($service_id);
//
//            if (!$service) {
//                $data = returnMessage(-1, 400, '', 'Service not found!');
//                return response($data, 400);
//            }
//
//            $cart = Carts::where('user_id', $user_id)
//                ->where('service_id', $service_id)
//                ->where('values', $values)
//                ->first();
//
//            if ($cart) {
//                $quantity = $cart->quantity + $quantity;
//
//                $cart->quantity = $quantity;
//                $cart->save();
//            } else {
//                $cart = new Carts();
//
//                $cart->service_id = $service_id;
//                $cart->user_id = $user_id;
//                $cart->quantity = $quantity;
//                $cart->values = $values;
//
//                $cart->save();
//            }
//
//            $data = returnMessage(1, 200, $cart, 'Success');
//            return response($data, 200);
//        } catch (\Exception $exception) {
//            $data = returnMessage(-1, 400, '', $exception->getMessage());
//            return response($data, 400);
//        }
//    }
//
//    /**
//     * Change quantity of a cart
//     *
//     * @OA\Post(
//     *     path="/api/carts/change-quantity/{id}",
//     *     summary="Change quantity of a cart",
//     *     tags={"Cart"},
//     *     @OA\Parameter(
//     *         description="Cart ID",
//     *         in="path",
//     *         name="id",
//     *         required=true,
//     *         @OA\Schema(
//     *             type="integer",
//     *             format="int64"
//     *         )
//     *     ),
//     *     @OA\RequestBody(
//     *         required=true,
//     *         @OA\JsonContent(
//     *             type="object",
//     *             @OA\Property(
//     *                 property="quantity",
//     *                 type="integer",
//     *                 example="1",
//     *                 description="Quantity of cart"
//     *             )
//     *         )
//     *     ),
//     *     @OA\Response(response=400, description="Bad request"),
//     *     @OA\Response(response=401, description="Unauthorized"),
//     *     @OA\Response(response=404, description="Cart not found!"),
//     *     @OA\Response(response=500, description="Internal Server Error")
//     * )
//     */
//    public function changeQuantity($id, Request $request)
//    {
//        try {
//            $qty = $request->input('quantity');
//            $cart = Carts::find($id);
//            if (!$cart) {
//                $data = returnMessage(-1, 404, '', 'Cart not found!');
//                return response($data, 404);
//            }
//
//            if ($qty < 1) {
//                $data = returnMessage(-1, 400, '', 'Quantity must be greater than 0!');
//                return response($data, 400);
//            }
//
//            $cart->quantity = $qty;
//            $cart->save();
//            $data = returnMessage(1, 200, $cart, 'Success');
//            return response($data, 200);
//        } catch (\Exception $exception) {
//            $data = returnMessage(-1, 400, '', $exception->getMessage());
//            return response($data, 400);
//        }
//    }
//
//    /**
//     * Remove cart
//     *
//     * @OA\Post(
//     *     path="/api/carts/remove/{id}",
//     *     tags={"Cart"},
//     *     summary="Remove cart",
//     *     description="Remove cart",
//     *     @OA\Parameter(
//     *         description="id",
//     *         in="path",
//     *         name="id",
//     *         required=true,
//     *         @OA\Schema(
//     *             type="integer",
//     *             format="int64"
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=200,
//     *         description="successful operation"
//     *     ),
//     *     @OA\Response(
//     *         response=400,
//     *         description="Invalid ID supplied"
//     *     ),
//     *     security={
//     *         {"bearerAuth": {}}
//     *     }
//     * )
//     */
//    public function removeCart($id)
//    {
//        try {
//            $cart = Carts::find($id);
//            $cart?->delete();
//            $data = returnMessage(1, 200, 'Remove cart success!', 'Remove cart success');
//            return response($data, 200);
//        } catch (\Exception $exception) {
//            $data = returnMessage(-1, 400, '', $exception->getMessage());
//            return response($data, 400);
//        }
//    }
//
//    /**
//     * Clear cart
//     *
//     * @OA\Post(
//     *     path="/api/carts/clear",
//     *     summary="Clear cart",
//     *     tags={"Cart"},
//     *     @OA\Response(response=401, description="Unauthorized"),
//     *     @OA\Response(response=200, description="Clear success"),
//     *     @OA\Response(response=400, description="Error")
//     * )
//     */
//    public function clearCart()
//    {
//        try {
//            $user = $this->user->toArray();
//
//            $carts = Carts::where('user_id', $user['id'])->delete();
//            $data = returnMessage(1, 200, 'Clear cart success!', 'Clear cart success');
//            return response($data, 200);
//        } catch (\Exception $exception) {
//            $data = returnMessage(-1, 400, '', $exception->getMessage());
//            return response($data, 400);
//        }
//    }
//}
