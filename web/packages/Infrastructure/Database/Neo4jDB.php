<?php

namespace packages\Infrastructure\Database;

use \Laudis\Neo4j\ClientBuilder;
use \Laudis\Neo4j\Authentication\Authenticate;

class Neo4jDB {

    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
        ->withDriver(
            'aura',
            'neo4j+s://aeb87557.production-orch-0064.neo4j.io:7687',
            Authenticate::basic('neo4j', '9D8oiNAv3XjrYa7O31nv0SAgq6iuzAXcV6AwKh7QIe8')
        )
        ->build();
    }


    public function call()
    {
        return $this->client;
    }
}