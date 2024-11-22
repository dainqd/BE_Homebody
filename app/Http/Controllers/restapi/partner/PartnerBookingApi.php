<?php

namespace App\Http\Controllers\restapi\partner;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingServices;
use App\Models\Services;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PartnerBookingApi extends Controller
{
    protected $partner;

    /**
     * Instantiate a new CheckoutController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->partner = JWTAuth::parseToken()->authenticate();
    }

    public function list(Request $request)
    {
        try {
            $userID = $this->partner['id'];
            $bookings = Booking::where('partner_id', $userID)
                ->where('status', '!=', BookingStatus::DELETED)
                ->orderByDesc('id')
                ->cursor()
                ->map(function (Booking $booking) use ($request) {
                    $item = $booking->toArray();

                    $booking_services = BookingServices::where('booking_id', $booking->id)
                        ->orderByDesc('id')
                        ->cursor()
                        ->map(function (BookingServices $bookingService) {
                            $data = $bookingService->toArray();
                            $service = Services::find($bookingService->service_id);
                            $data['service'] = $service->toArray();
                            return $data;
                        });

                    $item['booking_service'] = $booking_services->toArray();

                    return $item;
                });

            $res = $bookings->toArray();
            $data = returnMessage(1, 200, $res, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function detail($id)
    {
        try {
            $userID = $this->partner['id'];
            $booking = Booking::where('partner_id', $userID)
                ->where('status', BookingStatus::DELETED)
                ->where('id', $id)
                ->first();

            if (!$booking) {
                $data = returnMessage(-1, 400, null, 'Booking not found!');
                return response($data, 400);
            }

            $booking_services = BookingServices::where('booking_id', $booking->id)
                ->orderByDesc('id')
                ->cursor()
                ->map(function (BookingServices $bookingService) {
                    $data = $bookingService->toArray();
                    $service = Services::find($bookingService->service_id);
                    $data['service'] = $service->toArray();
                    return $data;
                });

            $res = $booking->toArray();
            $res['booking_service'] = $booking_services->toArray();

            $data = returnMessage(1, 200, $res, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $userID = $this->partner['id'];
            $booking = Booking::where('status', BookingStatus::DELETED)
                ->where('partner_id', $userID)
                ->where('id', $id)
                ->first();

            if (!$booking) {
                $data = returnMessage(-1, 400, null, 'Booking not found!');
                return response($data, 400);
            }

            $booking_services = BookingServices::where('booking_id', $booking->id)
                ->orderByDesc('id')
                ->cursor()
                ->map(function (BookingServices $bookingService) {
                    $data = $bookingService->toArray();
                    $service = Services::find($bookingService->service_id);
                    $data['service'] = $service->toArray();
                    return $data;
                });

            $res = $booking->toArray();
            $res['booking_service'] = $booking_services->toArray();

            $status = $request->input('status');
            if (!$status) {
                $data = returnMessage(-1, 400, null, 'Status not found!');
                return response($data, 400);
            }

            if ($booking->status == BookingStatus::CANCELED) {
                $data = returnMessage(-1, 400, null, 'Cannot cancel a reservation once it has been cancelled!');
                return response($data, 400);
            }

            if ($booking->status == BookingStatus::REJECTED) {
                $data = returnMessage(-1, 400, null, 'Cannot cancel a reservation once it has been rejected!');
                return response($data, 400);
            }

            if ($booking->status == BookingStatus::COMPLETED) {
                $data = returnMessage(-1, 400, null, 'Cannot cancel a reservation once it has been completed!');
                return response($data, 400);
            }

            $booking->status = $status;
            $booking->save();

            $data = returnMessage(1, 200, $res, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}