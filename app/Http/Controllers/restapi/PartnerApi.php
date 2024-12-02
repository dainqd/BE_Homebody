<?php

namespace App\Http\Controllers\restapi;

use App\Http\Controllers\Controller;
use App\Models\PartnerInformations;
use DateTime;
use Illuminate\Http\Request;

class PartnerApi extends Controller
{
    public function getHourlyIntervals(Request $request)
    {
        try {
            $partner_id = $request->input('partner_id');

            $partner = PartnerInformations::where('user_id', $partner_id)->first();
            if (!$partner) {
                $data = returnMessage(-1, 400, '', 'Partner not found!');
                return response($data, 400);
            }

            $time_working = $partner->time_working;

            $time_working = explode('-', $time_working);
            $startTime = $time_working[0];
            $endTime = $time_working[1];

            $intervals = [];

            $start = DateTime::createFromFormat('h:ia', $startTime);
            $end = DateTime::createFromFormat('h:ia', $endTime);

            while ($start < $end) {
                $next = clone $start;
                $next->modify('+1 hour');

                $intervals[] = $start->format('h') . ':00 ' . strtoupper($start->format('a')) . '-' . $next->format('h') . ':00 ' . strtoupper($next->format('a'));

                $start = $next;
            }

            $res = [
                'time_day' => $time_working,
                'time_start' => $startTime,
                'time_end' => $endTime,
                'time_interval' => $intervals
            ];

            $data = returnMessage(1, 200, $res, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

}
