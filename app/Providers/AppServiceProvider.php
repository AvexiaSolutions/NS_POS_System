<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Force HTTPS scheme only when accessed via a domain name (not localhost or IP address)
        if (str_starts_with(config('app.url'), 'https://') && !in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']) && !filter_var(request()->getHost(), FILTER_VALIDATE_IP)) {
            URL::forceScheme('https');
        }
    }
}
