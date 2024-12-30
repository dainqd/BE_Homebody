<?php

namespace App\Http\Controllers\admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingHistories;
use App\Models\BookingServices;
use App\Models\Services;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function list(Request $request)
    {
        try {
            $size = $request->input('size') ?? 10;
            $size = intval($size);
            $bookings = Booking::where('status', '!=', BookingStatus::DELETED)
                ->orderBy('id', 'desc')
                ->paginate($size);
            return view('admin.bookings.list', compact('bookings'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function detail(Request $request, $id)
    {
        try {
            $booking = Booking::where('id', $id)
                ->where('status', '!=', BookingStatus::DELETED)
                ->first();

            if (!$booking) {
                return redirect(route('error.not.found'));
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

            $booking_histories = BookingHistories::where('booking_id', $booking->id)
                ->orderByDesc('created_at')
                ->join('users', 'users.id', '=', 'booking_histories.user_id')
                ->select('booking_histories.*', 'users.full_name', 'users.email')
                ->get();

            return view('admin.bookings.detail', compact('booking', 'booking_services', 'booking_histories'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
