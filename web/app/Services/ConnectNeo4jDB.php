<?php

namespace App\Services;

use \Laudis\Neo4j\ClientBuilder;
use \Laudis\Neo4j\Authentication\Authenticate;

/**
 * Neo4jのDBを呼び出す処理をFacadeを使って共通化
 */
class ConnectNeo4jDB {

    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $database_url = ('database.connections.neo4j.url');
        $database_password = ('database.connections.neo4j.password');
        $database_user_name = ('database.connections.neo4j.username');

        $this->client = ClientBuilder::create()
        ->withDriver(
            'aura',
            $database_url,
            Authenticate::basic($database_user_name, $database_password)
        )
        ->build();
    }


    public function call()
    {
        return $this->client;
    }
}