<?php

namespace App\Http\Controllers\restapi\user;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Http\Controllers\Api;
use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutApi extends Api
{
    public function test()
    {
        return view('test');
    }


    public function processStripe(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $stripeToken = $request->input('stripeToken');
            $description = $request->input('description');

            $booking_id = $request->input('booking_id');

            $booking = Booking::find($booking_id);
            if (!$booking) {
                $data = returnMessage(-1, 400, null, 'Booking not found!');
                return response($data, 400);
            }

            if ($booking->status != BookingStatus::CONFIRMED) {
                $data = returnMessage(-1, 400, null, 'Booking not confirmed!');
                return response($data, 400);
            }

            if ($booking->type == BookingType::PAID) {
                $data = returnMessage(-1, 400, null, 'Booking already paid!');
                return response($data, 400);
            }

            $amount = $booking->total_price * 100;

            $charge = Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $stripeToken,
                'description' => $description ?? 'Thanh toán đơn hàng',
            ]);

            $success = $charge->status;
            if ($success != 'succeeded') {
                $data = returnMessage(-1, 200, null, 'Thanh toán thất bại!');
                return response($data, 200);
            }

            $booking->type = BookingType::PAID;

            $booking->save();

            $data = returnMessage(1, 200, $charge, 'Thanh toán thành công!');
            return response($data, 200);

        } catch (CardException $e) {
            $data = returnMessage(-1, 402, null, 'Thanh toán thất bại: ' . $e->getMessage());
            return response($data, 402);
        } catch (ApiErrorException $e) {
            $data = returnMessage(-1, 500, null, 'Stripe API error: ' . $e->getMessage());
            return response($data, 500);
        } catch (\Exception $e) {
            $data = returnMessage(-1, 400, null, 'Lỗi không xác định: ' . $e->getMessage());
            return response($data, 400);
        }
    }

    public function handleCheckout(Request $request)
    {
        try {
            $charge = Charge::create([
                'amount' => $request->input('amount') * 100,
                'currency' => 'usd',
                'source' => $request->input('stripeToken'),
                'description' => $request->input('description') ?? 'Thanh toán đơn hàng',
            ]);

            $data = returnMessage(1, 200, $charge, 'Thanh toán thành công!');
            return response($data, 200);
        } catch (CardException $e) {
            $data = returnMessage(-1, 402, '', 'Thanh toán thất bại: ' . $e->getMessage());
            return response($data, 402);
        } catch (ApiErrorException $e) {
            $data = returnMessage(-1, 500, '', 'Stripe API error: ' . $e->getMessage());
            return response($data, 500);
        } catch (\Exception $e) {
            $data = returnMessage(-1, 400, '', 'Lỗi không xác định: ' . $e->getMessage());
            return response($data, 400);
        }
    }

    public function createPaymentIntent(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $intent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            return response()->json([
                'clientSecret' => $intent->client_secret,
            ]);
        } catch (CardException $e) {
            $data = returnMessage(-1, 402, '', 'Thanh toán thất bại: ' . $e->getMessage());
            return response($data, 402);
        } catch (ApiErrorException $e) {
            $data = returnMessage(-1, 500, '', 'Stripe API error: ' . $e->getMessage());
            return response($data, 500);
        } catch (\Exception $e) {
            $data = returnMessage(-1, 400, '', 'Lỗi không xác định: ' . $e->getMessage());
            return response($data, 400);
        }
    }

    public function confirmPayment(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $intent = PaymentIntent::retrieve($request->payment_intent_id);
            $intent->confirm([
                'payment_method' => $request->payment_method,
            ]);

            return response()->json($intent);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
