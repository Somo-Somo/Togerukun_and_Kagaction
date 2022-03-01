<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HypothesisServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 
            \App\Repositories\Hypothesis\HypothesisRepositoryInterface::class,
            \App\Repositories\Hypothesis\HypothesisRepository::class,
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
