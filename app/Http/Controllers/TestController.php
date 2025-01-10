<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class TestController extends Api
{
    /**
     * @OA\Get(
     *     path="/api/translates/convert",
     *     summary="Translate",
     *     description="Translate",
     *     tags={"Translate"},
     *     @OA\Parameter(
     *         description="txt",
     *         in="query",
     *         name="txt",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *          @OA\Parameter(
     *          description="lang",
     *          in="query",
     *          name="lang",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Internal Server error"
     *     )
     * )
     */
    public function translate(Request $request)
    {
        $txt = $request->input('txt');
        $lang = $request->input('lang');

        $txt = language_helper($txt, $lang);

        $res = [
            'txt' => $txt,
            'lang' => $lang
        ];

        $data = returnMessage(1, 200, $res, 'Success');
        return response($data, 200);
    }
}
