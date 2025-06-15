<?php

namespace RechargeMeter\RechargeMeterService\Providers;

use Illuminate\Support\ServiceProvider;
use RechargeMeter\RechargeMeterService\Http\Clients\RechargeClient;
use RechargeMeter\RechargeMeterService\Http\Clients\UseTypeClient;
use RechargeMeter\RechargeMeterService\Services\RechargeService;
use RechargeMeter\RechargeMeterService\Services\UseTypeService;

class RechargeMeterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register the config file
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/recharge.php',
            'recharge'
        );

        // Register the RechargeClient as a singleton
        $this->app->singleton(RechargeClient::class, function ($app) {
            return new RechargeClient();
        });

        // Register the UseTypeClient as a singleton
        $this->app->singleton(UseTypeClient::class, function ($app) {
            return new UseTypeClient();
        });

        // Register the RechargeService
        $this->app->bind(RechargeService::class, function ($app) {
            return new RechargeService(
                $app->make(RechargeClient::class)
            );
        });

        // Register the UseTypeService
        $this->app->bind(UseTypeService::class, function ($app) {
            return new UseTypeService(
                $app->make(UseTypeClient::class)
            );
        });
    }

    public function boot(): void
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../../config/recharge.php' => config_path('recharge.php'),
        ], 'recharge-config');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
} 