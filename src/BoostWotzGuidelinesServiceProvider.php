<?php

namespace Wotzebra\BoostWotzGuidelines;

use Illuminate\Support\ServiceProvider;

class BoostWotzGuidelinesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Boost auto-discovers resources/boost/ directory.
    }
}
