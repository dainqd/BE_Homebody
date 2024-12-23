<?php

namespace App\Http\Controllers\restapi\partner;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingHistories;
use App\Models\BookingServices;
use App\Models\MyRevenues;
use App\Models\Revenues;
use App\Models\Services;
use Carbon\Carbon;
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
            $booking = Booking::where('status', '!=', BookingStatus::DELETED)
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
            $booking = Booking::where('status', '!=', BookingStatus::DELETED)
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

            $status = $request->input('status') ?? $booking->status;
            $reason_cancel = $request->input('reason_cancel');

            if ($status == BookingStatus::CANCELED) {
                if ($booking->status == BookingStatus::DELETED) {
                    $data = returnMessage(-1, 400, null, 'Booking already deleted!');
                    return response($data, 400);
                }

                if ($booking->status == BookingStatus::CONFIRMED) {
                    $data = returnMessage(-1, 400, null, 'Booking already confirmed!');
                    return response($data, 400);
                }

                if ($booking->status == BookingStatus::CANCELED) {
                    $data = returnMessage(-1, 400, null, 'Booking already canceled');
                    return response($data, 400);
                }

                if ($booking->status == BookingStatus::COMPLETED) {
                    $data = returnMessage(-1, 400, null, 'Booking already completed');
                    return response($data, 400);
                }
            }

            switch ($status) {
                case BookingStatus::PENDING:
                    $status = BookingStatus::PROCESSING;
                    break;
                case BookingStatus::PROCESSING:
                    $status = BookingStatus::CONFIRMED;
                    break;
                case BookingStatus::CANCELED:
                    $booking->reason_cancel = $reason_cancel;
                    $status = BookingStatus::CANCELED;
                    break;
                default:
                    $status = BookingStatus::COMPLETED;
                    break;
            }

            $booking->status = $status;
            $booking->save();

            if ($booking->status == BookingStatus::COMPLETED) {
                /* Insert revenues for admin view */
                $revenue = new Revenues();
                $revenue->total = $booking->total_price;
                $revenue->booking_id = $booking->id;

                $revenue->date = Carbon::now()->day;
                $revenue->month = Carbon::now()->month;
                $revenue->year = Carbon::now()->year;

                $revenue->save();

                /* Insert revenues for partner view */
                $my_revenue = new MyRevenues();
                $my_revenue->total = $booking->total_price;
                $my_revenue->booking_id = $booking->id;

                $my_revenue->date = Carbon::now()->day;
                $my_revenue->month = Carbon::now()->month;
                $my_revenue->year = Carbon::now()->year;
                $my_revenue->user_id = $booking->partner_id;
                $my_revenue->save();
            }

            /* Insert Booking history */
            $booking_history = new BookingHistories();
            $booking_history->booking_id = $booking->id;
            $booking_history->status = $status;
            $booking_history->notes = $booking->reason_cancel;
            $booking_history->user_id = $this->partner['id'];
            $booking_history->save();

            $data = returnMessage(1, 200, $res, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
