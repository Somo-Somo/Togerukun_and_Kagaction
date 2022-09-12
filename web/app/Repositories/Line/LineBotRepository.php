<?php

namespace App\Repositories\Line;

use App\Facades\Neo4jDB;
use App\Repositories\Line\LineBotRepositoryInterface;

class LineBotRepository implements LineBotRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    /**
     * ゴールをDB上で作成
     *
     * @param array $goal
     */
    public function findIfProjectExists(string $id)
    {
        $this->client->run(
            <<<'CYPHER'
                OPTIONAL MATCH  x = (user:User { line_user_id : $id })-[:HAS] -> (project:Project)
                RETURN x
                CYPHER,
            [
                'line_user_id' => $id,
            ]
        );
    }
}
