<?php

namespace App\Services;

use App\DTO\CurrencyRateStatus;

interface CurrencyRateInterface
{
    public const BTC = 'BTC';
    public const ETH = 'ETH';
    public const LTC = 'LTC';
    public const DOT = 'DOT';
    public const DOGE = 'DOGE';

    public const USD = 'USD';
    public const EUR = 'EUR';

    public const CRYPTO_CURRENCIES = [
        self::BTC => 'Bitcoin',
        self::ETH => 'Ethereum',
        self::LTC => 'Litecoin',
        self::DOT => 'Polkadot',
        self::DOGE => 'Dogecoin',
    ];

    public const DEFAULT_CURRENCY_TO = self::USD;

    public const AVAILABLE_CURRENCIES_TO = [
        self::USD,
        self::EUR,
    ];

    public function getCurrencyRateStatusList(array $currencyFromList, string $currencyTo, \DateTime $dateTime): array;

    public function getCurrencyRateStatus(string $currencyFrom, string $currencyTo, \DateTime $dateTime): CurrencyRateStatus;
}
