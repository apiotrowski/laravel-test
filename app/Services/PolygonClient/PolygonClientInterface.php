<?php

namespace App\Services\PolygonClient;

use JetBrains\PhpStorm\ArrayShape;

interface PolygonClientInterface
{
    #[ArrayShape(['open' => "float", 'close' => "float"])]
    public function getDailyOpenCloseCourses(string $currencyFrom, string $currencyTo, \DateTime $dateTime): array;
}
