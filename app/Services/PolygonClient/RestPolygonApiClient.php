<?php

namespace App\Services\PolygonClient;

use JetBrains\PhpStorm\ArrayShape;
use PolygonIO\Rest\Rest;

class RestPolygonApiClient implements PolygonClientInterface
{
    public function __construct()
    {
        $this->apiKey = 'HsXGDm0Acw5MuVVMGHM6T_cArLqN8Yfo';
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
