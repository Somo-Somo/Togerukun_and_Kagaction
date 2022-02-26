<?php

namespace App\Repositories\Project;

use Illuminate\Support\Facades\Neo4jDB;
use App\Repositories\Project\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function getProjectList($user_email)
    {
        $projectList = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email })-[:HAS]->(project:Project)
                RETURN project
                ORDER BY project
                CYPHER,
                [
                    'user_email' => $user_email, 
                ]
            );

        return $projectList;
    }

    public function create($project)
    {        
        $createdProject = $this->client->run(
                <<<'CYPHER'
                    MATCH (user:User { email : $user_email })
                    CREATE (user)-[
                                :HAS{since:localdatetime({timezone: 'Asia/Tokyo'})}
                            ]->(
                            project:Project {
                                name: $name, 
                                uuid: $uuid
                            })
                    RETURN project
                    CYPHER,
                    [
                        'name' => $project['name'], 
                        'uuid' => $project['uuid'], 
                        'user_email' => $project['created_by_user_email'], 
                    ]
                );

        return $createdProject;

    // $createdProject = $client->run(
        //     <<<'CYPHER'
        //         MATCH (user:User { email : $user_email })
        //         RETURN user
        //         CYPHER,
        //         [
        //             'name' => $project['name'], 
        //             'uuid' => $project['uuid'], 
        //             'user_email' => $project['created_by_user_email'], 
        //         ]
        //     );
    }
}
