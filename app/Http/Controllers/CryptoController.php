<?php

namespace App\Http\Controllers;

use App\Services\CurrencyRateInterface;
use GuzzleHttp\Exception\ClientException;
use Illuminate\View\View;

class CryptoController extends Controller
{
    private const TODAY = 'Today';

    private CurrencyRateInterface $currencyRateClient;

    /**
     * @param CurrencyRateInterface $currencyRateClient
     */
    public function __construct(CurrencyRateInterface $currencyRateClient)
    {
        $this->currencyRateClient = $currencyRateClient;
    }

    public function index(?string $currencyToParam = null, ?string $dateParam = null): View
    {
        $currentCurrencyTo = $currencyToParam ?? CurrencyRateInterface::DEFAULT_CURRENCY_TO;
        $currentDate = $dateParam ? new \DateTime($dateParam) : new \DateTime();

        try {
            $currencyRateStatusList = $this->currencyRateClient->getCurrencyRateStatusList(
                array_keys(CurrencyRateInterface::CRYPTO_CURRENCIES),
                $currentCurrencyTo,
                $currentDate
            );
        } catch (ClientException $e) {
            $errorMessage = 'An error occurred during getting currencies rate. Please try again ...';
        }

        return view('crypto.index', [
            'currencyRateStatusList' => $currencyRateStatusList ?? [],
            'currencyToList' => CurrencyRateInterface::AVAILABLE_CURRENCIES_TO,
            'currentCurrencyTo' => $currentCurrencyTo,
            'currentDate' => $dateParam ?? self::TODAY,
            'dateList' => self::generateAvailableDates(),
            'errorMessage' => $errorMessage ?? null
        ]);
    }

    private static function generateAvailableDates(): array
    {
        $from = new \DateTime();
        $to = new \DateTime('-365 days');

        $dateList = [self::TODAY];

        for ($date = clone $from; $date > $to; $date->modify('-1 day')) {
            $dateList[] = $date->format('Y-m-d');
        }

        return $dateList;
    }
}
