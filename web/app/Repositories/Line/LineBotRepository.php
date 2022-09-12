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
        $ifProjectExists = $this->client->run(
            <<<'CYPHER'
                OPTIONAL MATCH if_project_exists = (user:User { line_user_id : $line_user_id }) - [:HAS] -> (project:Project)
                RETURN if_project_exists
                CYPHER,
            [
                'line_user_id' => $id,
            ]
        );

        return $ifProjectExists->toArray();
    }
}
