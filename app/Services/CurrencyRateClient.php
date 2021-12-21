<?php

namespace App\Services;

use App\DTO\CurrencyRateStatus;
use App\Services\PolygonClient\PolygonClientInterface;

class CurrencyRateClient implements CurrencyRateInterface
{
    private PolygonClientInterface $client;

    public function __construct(PolygonClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCurrencyRateStatusList(array $currencyFromList, string $currencyTo, \DateTime $dateTime): array
    {
        $response = [];

        foreach ($currencyFromList as $currencyFrom) {
            $response[] = $this->getCurrencyRateStatus($currencyFrom, $currencyTo, $dateTime);
        }

        return $response;
    }

    public function getCurrencyRateStatus(string $currencyFrom, string $currencyTo, \DateTime $dateTime): CurrencyRateStatus
    {
        if (false === in_array($currencyFrom, array_keys(CurrencyRateInterface::CRYPTO_CURRENCIES), true)) {
            throw new \InvalidArgumentException(sprintf('Not supported currencyFrom %s.', $currencyFrom));
        }

        if (false === in_array($currencyTo, CurrencyRateInterface::AVAILABLE_CURRENCIES_TO, true)) {
            throw new \InvalidArgumentException(sprintf('Not supported currencyTo %s.', $currencyTo));
        }

        $currencyOpenCloseValues = $this->client->getDailyOpenCloseCourses($currencyFrom, $currencyTo, $dateTime);

        return new CurrencyRateStatus(
            $this->getCurrencyNameBySymbol($currencyFrom),
            $currencyFrom,
            $currencyTo,
            $currencyOpenCloseValues['open'],
            $currencyOpenCloseValues['close'],
        );
    }

    private function getCurrencyNameBySymbol(string $currencySymbol): string
    {
        if (true === empty(CurrencyRateInterface::CRYPTO_CURRENCIES[$currencySymbol])) {
            throw new \InvalidArgumentException(sprintf('Not found related CurrencyName from CurrencySymbol "%s"', $currencySymbol));
        }

        return CurrencyRateInterface::CRYPTO_CURRENCIES[$currencySymbol];
    }
}
