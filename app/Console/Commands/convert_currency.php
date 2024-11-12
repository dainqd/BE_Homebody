<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class convert_currency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:convert_currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->convertCurrency();
    }

    /**
     * Converts currency rates from USD to VND, VND to USD, CNY to VND, VND to CNY, USD to CNY, and CNY to USD
     *
     * This method is called by the `handle` method of this class, and it is responsible for
     * getting the current exchange rates from the API and inserting them into the database.
     *
     * @return void
     */
    private function convertCurrency()
    {
        /* Convert currency from USD to VND */
        $rateUSDtoVND = getExchangeRate('USD', 'VND', 1);
        $this->insertCurrency('USD', 'VND', $rateUSDtoVND);
        /* Convert currency from VND to USD */
        $rateVNDtoUSD = getExchangeRate('VND', 'USD', 1);
        $this->insertCurrency('VND', 'USD', $rateVNDtoUSD);
        /* Convert currency from CNY to VND */
        $rateCNYtoVND = getExchangeRate('CNY', 'VND', 1);
        $this->insertCurrency('CNY', 'VND', $rateCNYtoVND);
        /* Convert currency from VND to CNY */
        $rateVNDtoCNY = getExchangeRate('VND', 'CNY', 1);
        $this->insertCurrency('VND', 'CNY', $rateVNDtoCNY);
        /* Convert currency from USD to CNY */
        $rateUSDtoCNY = getExchangeRate('USD', 'CNY', 1);
        $this->insertCurrency('USD', 'CNY', $rateUSDtoCNY);
        /* Convert currency from CNY to USD */
        $rateCNYtoUSD = getExchangeRate('CNY', 'USD', 1);
        $this->insertCurrency('CNY', 'USD', $rateCNYtoUSD);
    }

    /**
     * Insert or update exchange rate to database
     *
     * @param string $fr from currency
     * @param string $to to currency
     * @param float $rate exchange rate
     *
     * @return void
     */
    private function insertCurrency($fr, $to, $rate)
    {
        try {
            $timeStart = Carbon::now()->startOfDay();
            $timeEnd = Carbon::now()->endOfDay();
            $item = Currency::where('to', $to)
                ->where('from', $fr)
                ->whereBetween('created_at', [$timeStart, $timeEnd])
                ->first();

            if ($item) {
                $item->rate = $rate;
                $item->save();
            } else {
                $currency = new Currency();
                $currency->from = $fr;
                $currency->to = $to;
                $currency->rate = $rate;
                $currency->created_at = Carbon::now();
                $currency->save();
            }
            Log::info('Insert currency: From: ' . $fr . ' To: ' . $to . 'With rate: ' . $rate . ' At ' . date('Y-m-d H:i:s'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
