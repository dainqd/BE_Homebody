<?php

namespace App\Http\Controllers\restapi;

use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class LocationApi extends Api
{
    protected $apiKeys = [
        '79b4b17bc4msh2cb9dbaadc30462p1f029ajsn6d21b28fc4af',
        '317cde09cdmsh1e9ff616e329ff6p1b3edejsnacd94136c963',
        'fd5b4a8a17mshf2a5dc6629e1906p1bcda4jsn0b81b87041d1',
    ];


    public function getLocation(Request $request)
    {
        try {
            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/locations/get-by-name",
     *     summary="Get Long and Lat from Address",
     *     description="Get Long and Lat from Address",
     *     tags={"Location"},
     *     @OA\Parameter(
     *         description="Address",
     *         in="query",
     *         name="address",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
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
    public function getLongAndLatFromAddress(Request $request)
    {
        $address = $request->input('address');

        if (!$address || $address == '') {
            $data = returnMessage(-1, 400, '', 'Address can not empty');
            return response($data, 400);
        }

        $encodedAddress = urlencode($address);
        $apiUrl = "https://google-map-places.p.rapidapi.com/maps/api/place/textsearch/json?query={$encodedAddress}&radius=1000&opennow=true&location=40%2C-110&language=en&region=en";

        $client = new Client();
        $locations = [];

        foreach ($this->apiKeys as $apiKey) {
            try {
                $response = $client->request('GET', $apiUrl, [
                    'headers' => [
                        'x-rapidapi-host' => 'google-map-places.p.rapidapi.com',
                        'x-rapidapi-key' => $apiKey
                    ]
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if (isset($data['results']) && count($data['results']) > 0) {
                    $locations = array_map(function ($element) {
                        return [
                            'address' => $element['formatted_address'],
                            'lat' => $element['geometry']['location']['lat'],
                            'lon' => $element['geometry']['location']['lng'],
                        ];
                    }, $data['results']);
                }
                break;

            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                continue;
            }
        }

        if (empty($locations)) {
            $data = returnMessage(-1, 400, '', 'Location not found!');
            return response($data, 400);
        }

        $rs = [
            'locations' => $locations,
        ];

        $data = returnMessage(1, 200, $rs, 'Success');
        return response($data, 200);
    }

    public function distanceAddressAndAddress()
    {

    }
}
