<?php

namespace App\Http\Controllers\restapi\user;

use App\Enums\BookingStatus;
use App\Enums\ServiceStatus;
use App\Http\Controllers\Api;
use App\Models\Booking;
use App\Models\BookingServices;
use App\Models\Services;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookingApi extends Api
{
    protected $user;

    /**
     * Instantiate a new BookingApi instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function list(Request $request)
    {
        try {
            $userID = $this->user['id'];
            $bookings = Booking::where('user_id', $userID)
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
            $userID = $this->user['id'];
            $booking = Booking::where('status', BookingStatus::DELETED)
                ->where('user_id', $userID)
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

    public function create(Request $request)
    {
        try {
            $userID = $this->user['id'];
            $booking = new Booking();

            $time_slot = $request->input('time_slot');
            $check_in = $request->input('check_in');
            $check_out = $request->input('check_out');
            $notes = $request->input('notes');

            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $address = $request->input('address');

            $total_price = 0;

            $booking->name = $name;
            $booking->email = $email;
            $booking->phone = $phone;
            $booking->address = $address;

            $status = BookingStatus::PROCESSING;

            $service_array_ = $request->input('service_array_');

            if (!$service_array_) {
                $data = returnMessage(-1, 400, null, 'Service not found!');
                return response($data, 400);
            }

            if (!$time_slot) {
                $data = returnMessage(-1, 400, null, 'Time slot not found!');
                return response($data, 400);
            }

            if (!$check_in) {
                $data = returnMessage(-1, 400, null, 'Check in not found!');
                return response($data, 400);
            }

            if (!$name || !$email || !$phone) {
                $data = returnMessage(-1, 400, null, 'Name, email, phone not found!');
                return response($data, 400);
            }

            $partner_id = 0;

            $first_service = $service_array_[0];
            $service_id = $first_service['id'];
            $service = Services::where('status', ServiceStatus::ACTIVE)
                ->where('id', $service_id)
                ->first();
            if ($service) {
                $partner_id = $service->user_id;
            }

            $booking->status = $status;
            $booking->user_id = $userID;
            $booking->time_slot = $time_slot;
            $booking->check_in = $check_in;
            $booking->check_out = $check_out;
            $booking->notes = $notes;

            $booking->partner_id = $partner_id;
            $booking->total_price = $total_price;

            $booking->save();

            foreach ($service_array_ as $key => $service_item) {
                $service_id = $service_item['id'];
                $service = Services::where('status', ServiceStatus::ACTIVE)
                    ->where('id', $service_id)
                    ->first();
                $quantity = $service_item['quantity'];
                if ($service) {
                    $booking_service = new BookingServices();
                    $booking_service->service_id = $service_id;
                    $booking_service->booking_id = $booking->id;
                    $booking_service->quantity = $quantity;
                    $price = (int)$quantity * $service->price;
                    $booking_service->price = $price;

                    $total_price += $price;

                    $booking->partner_id = $service->user_id;
                    $booking_service->save();
                }
            }

            $booking->total_price = $total_price;
            $booking->save();

            $data = returnMessage(1, 200, $booking, 'Create booking success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $userID = $this->user['id'];
            $booking = Booking::where('status', BookingStatus::DELETED)
                ->where('user_id', $userID)
                ->where('id', $id)
                ->first();

            if (!$booking) {
                $data = returnMessage(-1, 400, null, 'Booking not found!');
                return response($data, 400);
            }

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

            $data = returnMessage(1, 200, $booking, 'Update booking success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function cancel($id, Request $request)
    {
        try {
            $userID = $this->user['id'];
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

            if ($booking->status == BookingStatus::COMPLETED) {
                $data = returnMessage(-1, 400, null, 'Cannot cancel a reservation once it has been completed!');
                return response($data, 400);
            }

            $reason_cancel = $request->input('reason_cancel');
            $booking->reason_cancel = $reason_cancel;
            $booking->status = BookingStatus::CANCELED;
            $booking->updated_at = $userID;

            $booking->save();

            $data = returnMessage(1, 200, $booking, 'Cancel booking success!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
