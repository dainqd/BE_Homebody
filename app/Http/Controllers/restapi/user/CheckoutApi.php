<?php

namespace App\Http\Controllers\restapi\user;

use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Exception\CardException;
use Stripe\Exception\ApiErrorException;

class CheckoutApi extends Api
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }


    public function processStripe(Request $request)
    {
        try {
            $cardDetails = [
                'number' => $request->input('card_number'),
                'exp_month' => $request->input('exp_month'),
                'exp_year' => $request->input('exp_year'),
                'cvc' => $request->input('cvc'),
            ];

            $token = Token::create(['card' => $cardDetails]);
            $data = returnMessage(1, 200, $token, 'Token created successfully!');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
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
}
