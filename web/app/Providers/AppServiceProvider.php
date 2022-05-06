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
            \App\Repositories\Date\DateRepositoryInterface::class,
            \App\Repositories\Date\DateRepository::class,
        );
        $this->app->bind( 
            \App\Repositories\Comment\CommentRepositoryInterface::class,
            \App\Repositories\Comment\CommentRepository::class,
        );
        $this->app->bind( 
            \App\Repositories\Todo\TodoRepositoryInterface::class,
            \App\Repositories\Todo\TodoRepository::class,
        );
        $this->app->bind( 
            \App\Repositories\Goal\GoalRepositoryInterface::class,
            \App\Repositories\Goal\GoalRepository::class,
        );
        $this->app->bind( 
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class,
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
