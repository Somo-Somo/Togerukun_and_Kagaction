<?php

namespace App\Repositories\Goal;

use App\Facades\Neo4jDB;
use App\Repositories\Goal\GoalRepositoryInterface;

class GoalRepository implements GoalRepositoryInterface
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
    public function create(array $goal)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { uuid : $user_uuid }), (project:Project { uuid: $parent_uuid })
                CREATE (user)-[
                            :CREATED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                        ]->(
                           todo:Todo {
                                name: $name,
                                uuid: $uuid,
                                status: null,
                                limited: null
                        })-[
                            :IS_THE_GOAL_OF{at:localdatetime({timezone: 'Asia/Tokyo'})}
                        ]->(project)
                RETURN todo, project
                CYPHER,
            [
                'name' => $goal['name'],
                'uuid' => $goal['uuid'],
                'parent_uuid' => $goal['parent_uuid'],
                'user_uuid' => $goal['user_uuid'],
            ]
        );
    }
}
