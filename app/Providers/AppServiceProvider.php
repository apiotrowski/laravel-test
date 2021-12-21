<?php

namespace App\Providers;

//use App\Services\CurrencyRateClient;
//use App\Services\CurrencyRateInterface;
//use App\Services\PolygonClient\PolygonClientInterface;
//use App\Services\PolygonClient\RestPolygonApiClient;
use App\Services\CurrencyRateClient;
use App\Services\CurrencyRateInterface;
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




    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        //        $this->app->bind(PolygonClientInterface::class, function ($app) {
//            return new RestPolygonApiClient();
//        });
        $this->app->bind(CurrencyRateInterface::class, function ($app) {
//            $resetPolygonApiClient = $app->get(PolygonClientInterface::class);

            return new CurrencyRateClient(new RestPolygonApiClient());
        });
    }
}
