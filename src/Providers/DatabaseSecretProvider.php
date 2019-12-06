<?php

namespace dcotelo\secretDBdriver;

use Illuminate\Support\ServiceProvider;

class DatabaseSecretProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/secret.php' => config_path('secret.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
