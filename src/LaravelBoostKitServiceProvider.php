<?php

namespace Wotz\LaravelBoostKit;

use Illuminate\Support\ServiceProvider;

class LaravelBoostKitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Boost auto-discovers resources/boost/ directory.
    }
}