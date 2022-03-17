<?php

namespace App\Repositories\Hypothesis;

use App\Facades\Neo4jDB;
use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class HypothesisRepository implements HypothesisRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    /**
     * 選択されたプロジェクトの親仮説と子仮説と子仮説のゴールからの深さ（距離）を取得
     */
    public function getHypothesisList(string $projectUuid)
    {   
        $hypothesisList = $this->client->run(
            <<<'CYPHER'
                MATCH len = (project:Project{uuid: $project_uuid})<- [*] - (parent:Hypothesis)
                OPTIONAL MATCH (parent)<-[]-(child:Hypothesis)
                RETURN project.uuid,parent,collect(child),length(len)
                CYPHER,
                [
                    'project_uuid' => $projectUuid,
                ]
            );

        return $hypothesisList;
    }

    public function create($hypothesis)
    {
        $createdHypothesis = $this->client->run(
            <<<'CYPHER'
                MATCH   (user:User { email : $user_email }), 
                        (parent:Hypothesis { uuid: $parent_uuid }) - [*] -> (project:Project)
                CREATE (user)-[
                            :CREATED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                        ]->(
                           hypothesis:Hypothesis{
                                name: $name,
                                uuid: $uuid
                        })-[
                            :TO_ACHIEVE{at:localdatetime({timezone: 'Asia/Tokyo'})}  
                        ]->(parent)
                WITH project
                MATCH  len = (project:Project) <- [*] - (parent:Hypothesis)
                OPTIONAL MATCH (parent)<-[]-(child:Hypothesis)
                OPTIONAL MATCH (:User)-[todaysGoal:SET_TODAYS_GOAL]->(parent)
                RETURN project,parent,collect(child),length(len),todaysGoal
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

    public function update($hypothesis)
    {        
        $updatedHypothesis = $this->client->run(
                <<<'CYPHER'
                    MATCH (user:User { email : $user_email }), (hypothesis:Hypothesis { uuid: $uuid })
                    SET hypothesis.name = $name
                    WITH user,hypothesis
                    OPTIONAL MATCH x = (user)-[updated:UPDATED]->(hypothesis)
                    WHERE x IS NOT NULL 
                    SET updated.at = localdatetime({timezone: 'Asia/Tokyo'}) 
                    WITH user,hypothesis,x
                    WHERE x IS NULL
                    CREATE (user)-[:UPDATED{at:localdatetime({timezone: 'Asia/Tokyo'})}]->(hypothesis)
                    RETURN hypothesis
                    CYPHER,
                    [
                        'name' => $hypothesis['name'], 
                        'uuid' => $hypothesis['uuid'], 
                        'user_email' => $hypothesis['user_email'], 
                    ]
                );

        return $updatedHypothesis;
    }

    public function destroy(array $hypothesis)
    {
        $deletingHypothesis = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (hypothesis:Hypothesis{ uuid :$uuid }) - [r] -> (parent)
                CREATE (user)-[
                            :DELETED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                        ]->(hypothesis)
                DELETE r
                RETURN hypothesis
                CYPHER,
                [
                    'uuid' => $hypothesis['uuid'], 
                    'user_email' => $hypothesis['user_email'], 
                ]
            );
        return $deletingHypothesis;
    }

    public function updateStatus(array $hypothesis)
    {
        $updateHypothesisStatus = $this->client->run(
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
        return $updateHypothesisStatus;
    }

    public function destroyStatus(array $hypothesis)
    {
        $deleteHypothesisStatus = $this->client->run(
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
        return $deleteHypothesisStatus;
    }
}
