<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TodaysGoalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 
            \App\Repositories\TodaysGoal\TodaysGoalRepositoryInterface::class,
            \App\Repositories\TodaysGoal\TodaysGoalRepository::class,
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
