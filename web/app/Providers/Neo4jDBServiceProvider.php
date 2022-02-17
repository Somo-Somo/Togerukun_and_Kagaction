<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class Neo4jDBServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('neo4jDB', \packages\Infrastructure\Database\Neo4jDB::class);
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
