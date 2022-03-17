<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Neo4jDB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'neo4jDB';
    }
}