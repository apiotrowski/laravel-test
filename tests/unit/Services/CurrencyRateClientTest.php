<?php
namespace Services;

use App\DTO\CurrencyRateStatus;
use App\Services\CurrencyRateClient;
use App\Services\CurrencyRateInterface;
use App\Services\PolygonClient\RestPolygonApiClient;

class CurrencyRateClientTest extends \Codeception\Test\Unit
{
    public function testGetCurrencyRateStatusListForValidResponse()
    {
        $polygonApiClient = $this->getMockBuilder(RestPolygonApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $polygonApiClient->method('getDailyOpenCloseCourses')->willReturn([
            'open' => 10.00,
            'close' => 20.00,
        ]);

        $currencyRateClient = new CurrencyRateClient($polygonApiClient);

        $currencyFromList = array_keys(CurrencyRateInterface::CRYPTO_CURRENCIES);
        $currencyTo = CurrencyRateInterface::USD;
        $date = new \DateTime();

        $response = $currencyRateClient->getCurrencyRateStatusList(
            $currencyFromList,
            $currencyTo,
            $date
        );

        $this->assertCount(count($currencyFromList), $response);

        foreach ($response as $key => $responseItem) {
            $currencyFrom = $currencyFromList[$key];

            $apiResponse = $polygonApiClient->getDailyOpenCloseCourses($currencyFrom, $currencyTo, $date);

            $this->assertInstanceOf(CurrencyRateStatus::class, $responseItem);

            $this->assertEquals($currencyFrom, $responseItem->getCurrencySymbol());
            $this->assertEquals(CurrencyRateInterface::CRYPTO_CURRENCIES[$currencyFrom], $responseItem->getCurrencyName());
            $this->assertSame($apiResponse['open'], $responseItem->getOpeningValue());
            $this->assertSame($apiResponse['close'], $responseItem->getCloseValue());
            $this->assertTrue($responseItem->isValid());
            $this->assertSame(100.00, $responseItem->getOpenCloseRatio());
        }
    }

    /**
     * @dataProvider dataProviderForGetCurrencyRateStatusForValidResponse
     */
    public function testGetCurrencyRateStatusForValidResponse($requestParams, $polygonApiResponse, $expectedResponse): void
    {
        $polygonApiClient = $this->getMockBuilder(RestPolygonApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $polygonApiClient->method('getDailyOpenCloseCourses')->willReturn($polygonApiResponse);

        $apiResponse = $polygonApiClient->getDailyOpenCloseCourses($requestParams['currencyFrom'], $requestParams['currencyTo'], $requestParams['date']);

        $currencyRateClient = new CurrencyRateClient($polygonApiClient);
        $response = $currencyRateClient->getCurrencyRateStatus($requestParams['currencyFrom'], $requestParams['currencyTo'], $requestParams['date']);

        $this->assertEquals($expectedResponse['currencySymbol'], $response->getCurrencySymbol());
        $this->assertEquals($expectedResponse['currencyName'], $response->getCurrencyName());
        $this->assertSame($apiResponse['open'], $response->getOpeningValue());
        $this->assertSame($apiResponse['close'], $response->getCloseValue());
        $this->assertSame($expectedResponse['valid'], $response->isValid());
        $this->assertSame($expectedResponse['change'], $response->getOpenCloseRatio());
    }

    /**
     * @dataProvider dataProviderForGetCurrencyRateStatusForInvalidArguments
     */
    public function testGetCurrencyRateStatusForInvalidArguments($requestParams)
    {
        $this->expectException(\InvalidArgumentException::class);

        $polygonApiClient = $this->getMockBuilder(RestPolygonApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $polygonApiClient->method('getDailyOpenCloseCourses')->willReturn([
            'open' => 10.00,
            'close' => 20.00,
        ]);

        $currencyRateClient = new CurrencyRateClient($polygonApiClient);
        $currencyRateClient->getCurrencyRateStatus($requestParams['currencyFrom'], $requestParams['currencyTo'], $requestParams['date']);
    }

    public function dataProviderForGetCurrencyRateStatusForValidResponse(): array
    {
        return [
            [
                [
                    'currencyFrom' => CurrencyRateInterface::BTC,
                    'currencyTo' => CurrencyRateInterface::USD,
                    'date' => new \DateTime(),
                ],
                [
                    'open' => 10.00,
                    'close' => 20.00,
                ],
                [
                    'currencySymbol' => CurrencyRateInterface::BTC,
                    'currencyName' => CurrencyRateInterface::CRYPTO_CURRENCIES[CurrencyRateInterface::BTC],
                    'valid' => true,
                    'change' => 100.0
                ]
            ],
            [
                [
                    'currencyFrom' => CurrencyRateInterface::ETH,
                    'currencyTo' => CurrencyRateInterface::USD,
                    'date' => new \DateTime(),
                ],
                [
                    'open' => 20.00,
                    'close' => 10.00,
                ],
                [
                    'currencySymbol' => CurrencyRateInterface::ETH,
                    'currencyName' => CurrencyRateInterface::CRYPTO_CURRENCIES[CurrencyRateInterface::ETH],
                    'valid' => true,
                    'change' => -50.0
                ]
            ],
            [
                [
                    'currencyFrom' => CurrencyRateInterface::LTC,
                    'currencyTo' => CurrencyRateInterface::USD,
                    'date' => new \DateTime(),
                ],
                [
                    'open' => 5.00,
                    'close' => 15.00,
                ],
                [
                    'currencySymbol' => CurrencyRateInterface::LTC,
                    'currencyName' => CurrencyRateInterface::CRYPTO_CURRENCIES[CurrencyRateInterface::LTC],
                    'valid' => true,
                    'change' => 200.0
                ]
            ],
            [
                [
                    'currencyFrom' => CurrencyRateInterface::DOT,
                    'currencyTo' => CurrencyRateInterface::USD,
                    'date' => new \DateTime(),
                ],
                [
                    'open' => 200.00,
                    'close' => 20.00,
                ],
                [
                    'currencySymbol' => CurrencyRateInterface::DOT,
                    'currencyName' => CurrencyRateInterface::CRYPTO_CURRENCIES[CurrencyRateInterface::DOT],
                    'valid' => true,
                    'change' => -90.0
                ]
            ],
            [
                [
                    'currencyFrom' => CurrencyRateInterface::DOGE,
                    'currencyTo' => CurrencyRateInterface::USD,
                    'date' => new \DateTime(),
                ],
                [
                    'open' => 10.00,
                    'close' => 20.00,
                ],
                [
                    'currencySymbol' => CurrencyRateInterface::DOGE,
                    'currencyName' => CurrencyRateInterface::CRYPTO_CURRENCIES[CurrencyRateInterface::DOGE],
                    'valid' => true,
                    'change' => 100.0
                ]
            ],
            [
                [
                    'currencyFrom' => CurrencyRateInterface::DOGE,
                    'currencyTo' => CurrencyRateInterface::USD,
                    'date' => new \DateTime(),
                ],
                [
                    'open' => 0.00,
                    'close' => 0.00,
                ],
                [
                    'currencySymbol' => CurrencyRateInterface::DOGE,
                    'currencyName' => CurrencyRateInterface::CRYPTO_CURRENCIES[CurrencyRateInterface::DOGE],
                    'valid' => false,
                    'change' => 0.0
                ]
            ],
        ];
    }

    public function dataProviderForGetCurrencyRateStatusForInvalidArguments(): array
    {
        return [
            [
                [
                    'currencyFrom' => CurrencyRateInterface::BTC,
                    'currencyTo' => 'Invalid CurrencyTo',
                    'date' => new \DateTime(),
                ]
            ],
            [
                [
                    'currencyFrom' => 'Invalid CurrencyFrom',
                    'currencyTo' => CurrencyRateInterface::USD,
                    'date' => new \DateTime(),
                ]
            ]
        ];
    }
}
