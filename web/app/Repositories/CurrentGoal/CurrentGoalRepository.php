<?php

namespace App\Repositories\CurrentGoal;

use App\Facades\Neo4jDB;
use App\Repositories\CurrentGoal\CurrentGoalRepositoryInterface;

class CurrentGoalRepository implements CurrentGoalRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function updateCurrentGoal(array $hypothesis)
    {
        $updateHypothesisCurrentGoal = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (hypothesis:Hypothesis { uuid: $uuid })
                CREATE (user) - [
                    currentGoal: SET_CURRENT_GOAL{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ] -> (hypothesis)
                RETURN hypothesis
                CYPHER,
                [
                    'uuid' => $hypothesis['uuid'], 
                    'user_email' => $hypothesis['user_email'], 
                ]
            );
        return;
    }

    public function destroyCurrentGoal(array $hypothesis)
    {
        $deleteHypothesisCurrentGoal = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - 
                [currentGoal: SET_CURRENT_GOAL]
                ->(hypothesis:Hypothesis { uuid: $uuid })
                DELETE currentGoal
                RETURN hypothesis
                CYPHER,
                [
                    'uuid' => $hypothesis['uuid'], 
                    'user_email' => $hypothesis['user_email'], 
                ]
            );
        return;
    }
}
