<?php

namespace App\Providers;

use App\Services\CurrencyRateClient;
use App\Services\CurrencyRateInterface;
use App\Services\PolygonClient\PolygonClientInterface;
use App\Services\PolygonClient\RestPolygonApiClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PolygonClientInterface::class, function ($app) {
            $apiKey = config('services.polygon_api_key');
            return new RestPolygonApiClient($apiKey);
        });
        $this->app->bind(CurrencyRateInterface::class, function ($app) {
            $restPolygonApiClient = $app->get(PolygonClientInterface::class);

            return new CurrencyRateClient($restPolygonApiClient);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
