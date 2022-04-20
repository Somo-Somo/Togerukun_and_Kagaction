<?php

namespace App\Repositories\Project;

use App\Facades\Neo4jDB;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Support\Str;

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
                                :HAS{at:localdatetime({timezone: 'Asia/Tokyo'})}
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
    }

    public function update($project)
    {        
        $createdProject = $this->client->run(
                <<<'CYPHER'
                    MATCH (user:User { email : $user_email }), (project:Project { uuid: $uuid })
                    SET project.name = $name
                    WITH user,project
                    OPTIONAL MATCH x = (user)-[updated:UPDATED]->(project)
                    WHERE x IS NOT NULL 
                    SET updated.at = localdatetime({timezone: 'Asia/Tokyo'}) 
                    WITH user,project,x
                    WHERE x IS NULL
                    CREATE (user)-[:UPDATED{at:localdatetime({timezone: 'Asia/Tokyo'})}]->(project)
                    RETURN project
                    CYPHER,
                    [
                        'name' => $project['name'], 
                        'uuid' => $project['uuid'], 
                        'user_email' => $project['user_email'], 
                    ]
                );

        return $createdProject;
    }

    public function destroy(array $project)
    {
        $deletedDataFromDB = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (:User)-[has:HAS]->(project:Project{ uuid :$uuid })
                CREATE (user)-[
                            :DELETED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                        ]->(project)
                DELETE has
                RETURN project
                CYPHER,
                [
                    'uuid' => $project['uuid'], 
                    'user_email' => $project['user_email'], 
                ]
            );

        return $deletedDataFromDB;
    }


    /**
     * ※絶対にこんな書き方して言い訳がない
     * 会員登録後の使い方のテンプレをKagaction内で表示する
     */
    public function generateInitialTemplate(string $user_email)
    {
        $this->client->run(
            <<<'CYPHER'
                    MATCH (user:User{email:$user_email})
                    CREATE (user) - [:HAS{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (:Project{name:'仕事', uuid:$project_work_uuid})
                    CREATE (user) - [:HAS{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (:Project{name:'生活', uuid:$project_life_uuid})
                CYPHER,
                [
                    'user_email' => $user_email,
                    'project_work_uuid' => (string) Str::uuid(),
                    'project_life_uuid' => (string) Str::uuid(),
                ]
            );
    }
}
