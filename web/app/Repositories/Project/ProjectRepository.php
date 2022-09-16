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

    /**
     * プロジェクト一覧をDBから取得
     *
     * @param int $user_id
     * @return $project_list
     */
    public function getProjectList(int $user_id)
    {
        $project_list = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { user_id : $user_id })-[:HAS]->(project:Project)
                RETURN project
                ORDER BY project
                CYPHER,
            [
                'user_id' => $user_id,
            ]
        );

        return $project_list;
    }

    /**
     * プロジェクトをDB上で作成
     *
     * @param array $project
     * @return $created_project
     */
    public function create(array $project)
    {
        $created_project = $this->client->run(
            <<<'CYPHER'
                    MATCH (user:User { user_id : $user_id })
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
                'user_id' => $project['created_by_user_id'],
            ]
        );

        return $created_project;
    }

    /**
     * プロジェクトの名前をDB上で更新
     *
     * @param array $project
     */
    public function update(array $project)
    {
        $this->client->run(
            <<<'CYPHER'
                    MATCH (user:User { user_id : $user_id }), (project:Project { uuid: $uuid })
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
                'user_id' => $project['user_id'],
            ]
        );
    }

    /**
     * プロジェクトとユーザーを繋ぐリレーションを削除
     *
     * @param array $project
     */
    public function destroy(array $project)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { user_id : $user_id }), (:User)-[has:HAS]->(project:Project{ uuid :$uuid })
                CREATE (user)-[
                            :DELETED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                        ]->(project)
                DELETE has
                RETURN project
                CYPHER,
            [
                'uuid' => $project['uuid'],
                'user_id' => $project['user_id'],
            ]
        );
    }


    /**
     * ※絶対にこんな書き方して言い訳がない
     * 会員登録後の使い方のテンプレをKagaction内で表示する
     *
     * @param string $user_id
     */
    public function generateInitialTemplate(int $user_id)
    {
        $this->client->run(
            <<<'CYPHER'
                    MATCH (user:User{user_id:$user_id})
                    CREATE (user) - [:HAS{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (:Project{name:'仕事', uuid:$project_work_uuid})
                    CREATE (user) - [:HAS{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (:Project{name:'生活', uuid:$project_life_uuid})
                CYPHER,
            [
                'user_id' => $user_id,
                'project_work_uuid' => (string) Str::uuid(),
                'project_life_uuid' => (string) Str::uuid(),
            ]
        );
    }
}
