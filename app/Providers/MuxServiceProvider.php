<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MuxPhp\Api\MetricsApi;
use MuxPhp\Mux;
class MuxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
