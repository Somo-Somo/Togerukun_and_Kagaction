<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 
            \App\Repositories\Project\ProjectRepositoryInterface::class,
            \App\Repositories\Project\ProjectRepository::class,
        );
        $this->app->bind( 
            \App\Repositories\TodaysGoal\TodaysGoalRepositoryInterface::class,
            \App\Repositories\TodaysGoal\TodaysGoalRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() == 'production') {
            \URL::forceScheme('https');
        }
    }
}
