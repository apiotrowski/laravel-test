<?php

namespace App\Services\PolygonClient;

use JetBrains\PhpStorm\ArrayShape;
use PolygonIO\Rest\Rest;

class RestPolygonApiClient implements PolygonClientInterface
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    #[ArrayShape(['open' => "float", 'close' => "float"])]
    public function getDailyOpenCloseCourses(string $currencyFrom, string $currencyTo, \DateTime $dateTime): array
    {
        $rest = new Rest($this->apiKey);

        $currencyCourses = $rest->crypto()->dailyOpenClose()->get($currencyFrom, $currencyTo, $dateTime->format("Y-m-d"));

        return array(
            'open' => $currencyCourses['open'],
            'close' => $currencyCourses['close'],
        );
    }
}
