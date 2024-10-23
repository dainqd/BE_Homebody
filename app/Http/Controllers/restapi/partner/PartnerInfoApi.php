<?php

namespace App\Http\Controllers\restapi\partner;

use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerInfoApi extends Api
{
    public function saveInfo(Request $request)
    {
        try {
            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
