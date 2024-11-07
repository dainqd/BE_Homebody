<?php

namespace App\Http\Controllers\restapi;

use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CurrencyApi extends Api
{
    /**
     * @OA\Get(
     *     path="/api/currencies/convert",
     *     summary="Convert currency",
     *     description="Convert an amount from one currency to another.",
     *     tags={"Currency"},
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=true,
     *         description="Currency code to convert from",
     *         @OA\Schema(type="string", example="USD")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=true,
     *         description="Currency code to convert to",
     *         @OA\Schema(type="string", example="VND")
     *     ),
     *     @OA\Parameter(
     *         name="amount",
     *         in="query",
     *         required=true,
     *         description="Amount to convert",
     *         @OA\Schema(type="number", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful conversion",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="from", type="string", example="USD"),
     *             @OA\Property(property="to", type="string", example="VND"),
     *             @OA\Property(property="amount", type="number", example=1),
     *             @OA\Property(property="converted_amount", type="number", example=23000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, check parameters"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function convert(Request $request)
    {
        try {
            $fr = $request->input('from');
            $to = $request->input('to');
            $amount = $request->input('amount');

            $rate = getExchangeRate($fr, $to, $amount);

            $rs = [
                'from' => $fr,
                'to' => $to,
                'amount' => $amount * $rate,
                'rate' => $rate,
            ];

            $data = returnMessage(1, 200, $rs, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
