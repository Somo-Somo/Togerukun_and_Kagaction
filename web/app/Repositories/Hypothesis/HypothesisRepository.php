<?php

namespace App\Repositories\Hypothesis;

use Illuminate\Support\Facades\Neo4jDB;
use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class HypothesisRepository implements HypothesisRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function create($hypothesis)
    {
        $createdHypothesis = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (parent:Hypothesis { uuid: $parent_uuid })
                CREATE (user)-[
                            :CREATED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                        ]->(
                           hypothesis:Hypothesis {
                                name: $name,
                                uuid: $uuid,
                                status: null,
                                limited: null
                        })-[
                            :TO_ACHIEVE{since:localdatetime({timezone: 'Asia/Tokyo'})}  
                        ]->(parent)
                RETURN hypothesis, parent
                CYPHER,
                [
                    'name' => $hypothesis['name'], 
                    'uuid' => $hypothesis['uuid'], 
                    'parent_uuid' => $hypothesis['parent_uuid'], 
                    'user_email' => $hypothesis['created_by_user_email'], 
                ]
            );

        return $createdHypothesis;
    }
}
