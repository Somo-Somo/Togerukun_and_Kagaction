<?php

namespace App\Repositories\User;

use App\Facades\Neo4jDB;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    /**
     * ユーザーの情報をDBに登録
     *
     * @param array $user
     * @return $create_user
     */
    public function register(array $user)
    {
        $create_user = $this->client->run(
            <<<'CYPHER'
                MATCH ( onboarding:Onboarding { name : 'onboarding' } )
                CREATE (
                    user:User {
                        user_id: $user_id,
                        name: $name,
                        uuid: $uuid,
                        email: $email,
                        password: $password,
                        created_at: localdatetime({timezone: 'Asia/Tokyo'})
                    }) - [:NOT_EXECUTE] -> (onboarding)
                RETURN user
                CYPHER,
            [
                'user_id' => $user['id'],
                'uuid' => $user['uuid'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password']
            ]
        );
        return $create_user;
    }

    /**
     * すでにオンボーディングを終えているかDB上で確認
     *
     * @param string $user_email
     * @return $onboarding_done_in_array オンボーディングモデルを配列上にしたもの
     */
    public function whetherExecuteOnboarding(string $user_email)
    {
        $onboarding = $this->client->run(
            <<<'CYPHER'
                OPTIONAL MATCH (user:User{email:$user_email}) - [:NOT_EXECUTE] -> (onboarding:Onboarding)
                RETURN onboarding
                CYPHER,
            [
                'user_email' => $user_email,
            ]
        );
        $onboarding_done_in_array = $onboarding->toArray()[0]['onboarding'];
        return $onboarding_done_in_array;
    }

    /**
     * オンボーディングを終了したことをDBに
     *
     * @param array $onboarding
     */
    public function finishedOnboarding(array $onboarding)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User{email:$user_email}) - [not_execute:NOT_EXECUTE] -> (:Onboarding)
                CREATE (user)-[
                    :HAS{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ]->(
                project:Project {
                    name: $project_name,
                    uuid: $project_uuid
                })
                CREATE (user)-[
                    :CREATED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ]->(
                   todo:Todo {
                        name: $goal_name,
                        uuid: $goal_uuid
                })-[
                    :IS_THE_GOAL_OF{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ]->(project)
                CREATE (user) - [
                    :DATE { on: $goal_date }
                ] -> (todo)
                DELETE not_execute
                RETURN user
                CYPHER,
            [
                'project_name' => $onboarding['project']['name'],
                'project_uuid' => $onboarding['project']['uuid'],
                'goal_name' => $onboarding['goal']['name'],
                'goal_uuid' => $onboarding['goal']['uuid'],
                'goal_date' => $onboarding['goal']['date'],
                'user_email' => $onboarding['created_by_user_email']
            ]
        );
    }

    /**
     * ユーザーが持っているプロジェクトとTodoを取得
     *
     * @param string $user_email
     * @return $user_has_project_and_todo
     */
    public function getUserHasProjetAndTodo(string $user_email)
    {
        $user_has_project_and_todo = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User{email:$user_email}) - [:HAS] -> (project:Project),
                    len = (project) <- [r*] - (parent:Todo)
                OPTIONAL MATCH (parent)<-[]-(child:Todo)
                OPTIONAL MATCH (:User)-[date:DATE]->(parent)
                OPTIONAL MATCH (:User)-[accomplish:ACCOMPLISHED]->(parent)
                OPTIONAL MATCH (parent)<-[:TO]-(comment:Comment)
                OPTIONAL MATCH comments = (user)-[:CREATED]->(comment:Comment)
                OPTIONAL MATCH causes = (parent)-[:UNAVAILABLE_CAUSE]->(cause:Cause)
                WITH project,parent,r,child,len,date,accomplish,comments,causes ORDER BY comment, cause
                RETURN project,parent,r,collect(DISTINCT child) AS childs,length(len),date,accomplish,collect(DISTINCT comments) AS comments, collect(DISTINCT causes) AS causes
                ORDER BY r
                CYPHER,
            [
                'user_email' => $user_email,
            ]
        );

        return $user_has_project_and_todo;
    }
}
