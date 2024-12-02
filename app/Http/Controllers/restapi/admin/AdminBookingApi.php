<?php

namespace App\Http\Controllers\restapi\admin;

use App\Enums\BookingStatus;
use App\Enums\ServiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingServices;
use App\Models\Services;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminBookingApi extends Controller
{
    protected $admin;

    /**
     * Instantiate a new CheckoutController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->admin = JWTAuth::parseToken()->authenticate();
    }

    public function list(Request $request)
    {
        try {
            $bookings = Booking::where('status', '!=', BookingStatus::DELETED)
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
            $booking = Booking::where('status', BookingStatus::DELETED)
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
            $userID = $this->admin['id'];

            $booking = Booking::where('status', BookingStatus::DELETED)
                ->where('user_id', $userID)
                ->where('id', $id)
                ->first();

            if (!$booking) {
                $data = returnMessage(-1, 400, null, 'Booking not found!');
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

            $status = $request->input('status');
            $booking->status = $status;

            $time_slot = $request->input('time_slot');
            $check_in = $request->input('check_in');
            $check_out = $request->input('check_out');
            $notes = $request->input('notes');

            $service_array_ = $request->input('service_array_');
            $quantity_array_ = $request->input('quantity_array_');

            $booking->time_slot = $time_slot;
            $booking->check_in = $check_in;
            $booking->check_out = $check_out;
            $booking->notes = $notes;

            $booking->updated_at = $userID;

            $booking->save();

            foreach ($service_array_ as $key => $value) {
                $service_id = $value;
                $service = Services::where('status', ServiceStatus::ACTIVE)
                    ->where('id', $service_id)
                    ->first();
                $quantity = $quantity_array_[$key];
                if ($service) {
                    $booking_service = BookingServices::where('service_id', $service_id)
                        ->where('booking_id', $booking->id)
                        ->first();

                    if ($booking_service) {
                        $booking_service->quantity += $quantity;
                        $price = (int)$quantity * $service->price;
                        $booking_service->price = $price;
                    } else {
                        $booking_service = new BookingServices();
                        $booking_service->service_id = $service_id;
                        $booking_service->booking_id = $booking->id;
                        $booking_service->quantity = $quantity;
                        $price = (int)$quantity * $service->price;
                        $booking_service->price = $price;
                    }

                    $booking_service->save();
                }
            }

            $res = $booking->toArray();

            $data = returnMessage(1, 200, $res, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function delete($id)
    {
        try {
            $userID = $this->admin['id'];
            $booking = Booking::where('status', BookingStatus::DELETED)
                ->where('user_id', $userID)
                ->where('id', $id)
                ->first();

            if (!$booking) {
                $data = returnMessage(-1, 400, null, 'Booking not found!');
                return response($data, 400);
            }

            $booking->status = BookingStatus::DELETED;
            $booking->updated_at = $userID;

            $booking->save();

            $data = returnMessage(1, 200, $booking, 'Delete booking success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
