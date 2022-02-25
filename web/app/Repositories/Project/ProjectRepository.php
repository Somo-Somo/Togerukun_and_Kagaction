<?php

namespace App\Repositories\Project;

use Illuminate\Support\Facades\Neo4jDB;
use App\Repositories\Project\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function register($user)
    {
        $client = Neo4jDB::call();
        $client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email })
                CREATE (user) - [
                        has:has{since:localdatetime({timezone:'Asia/Tokyo'})}
                    ] -> (
                    project:Project {
                        name: $name, 
                        uuid: $uuid,
                    })
                RETURN project
                CYPHER,
                [
                    'name' => $project['name'], 
                    'uuid' => $project['uuid'], 
                    'user_email' => $project['created_by_user_email'], 
                ]);
    }
}
