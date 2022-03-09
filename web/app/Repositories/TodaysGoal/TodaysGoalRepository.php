<?php

namespace App\Repositories\TodaysGoal;

use Illuminate\Support\Facades\Neo4jDB;
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
                SET hypothesis.status = $status
                WITH user,hypothesis
                OPTIONAL MATCH x = (:User)-[evaluated:EVALUATED]->(hypothesis)
                WHERE x IS NOT NULL 
                DELETE evaluated
                WITH user,hypothesis
                CREATE (user)-[:EVALUATED{at:localdatetime({timezone: 'Asia/Tokyo'})}]->(hypothesis)
                RETURN hypothesis
                CYPHER,
                [
                    'uuid' => $hypothesis['uuid'], 
                    'status' => $hypothesis['status'], 
                    'user_email' => $hypothesis['user_email'], 
                ]
            );
        return ;
    }

    public function destroyTodaysGoal(array $hypothesis)
    {
        $deleteHypothesisTodaysGoal = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email })-[evaluated:EVALUATED]->(hypothesis:Hypothesis { uuid: $uuid })
                DELETE evaluated
                REMOVE hypothesis.status
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
