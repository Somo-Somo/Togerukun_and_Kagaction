<?php

namespace App\Repositories\TodaysGoal;

use App\Facades\Neo4jDB;
use App\Repositories\TodaysGoal\TodaysGoalRepositoryInterface;

class TodaysGoalRepository implements TodaysGoalRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function updateTodaysGoal(array $hypothesis)
    {
        $updateHypothesisTodaysGoal = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (hypothesis:Hypothesis { uuid: $uuid })
                CREATE (user) - [
                    todaysGoal: SET_TODAYS_GOAL{at:localdatetime({timezone: 'Asia/Tokyo'})}
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

    public function destroyTodaysGoal(array $hypothesis)
    {
        $deleteHypothesisTodaysGoal = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - 
                [todaysGoal: SET_TODAYS_GOAL]
                ->(hypothesis:Hypothesis { uuid: $uuid })
                DELETE todaysGoal
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
