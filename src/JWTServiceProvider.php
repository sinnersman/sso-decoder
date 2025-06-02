<?php

namespace RiskySetiawan\SSODecoder;

use Illuminate\Support\ServiceProvider;

class JWTServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Optional: bind helper if needed
    }

    public function boot()
    {
        // Nothing to boot currently
    }
}