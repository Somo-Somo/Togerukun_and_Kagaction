<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 
            \App\Repositories\Goal\GoalRepositoryInterface::class,
            \App\Repositories\Goal\GoalRepository::class,
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
