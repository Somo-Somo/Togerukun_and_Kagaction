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

    public function register($user)
    {
        $createUser = $this->client->run(
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
        return $createUser;
    }

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

        return $onboarding->toArray()[0]['onboarding'];
    }

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
        return;
    }

    public function getUserHasProjetAndTodo(string $user_email)
    {
        $userHasProjetAndTodo = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User{email:$user_email}) - [:HAS] -> (project:Project),
                    len = (project) <- [r*] - (parent:Todo)
                OPTIONAL MATCH (parent)<-[]-(child:Todo)
                OPTIONAL MATCH (:User)-[date:DATE]->(parent)
                OPTIONAL MATCH (:User)-[accomplish:ACCOMPLISHED]->(parent)
                OPTIONAL MATCH (parent)<-[:TO]-(comment:Comment)
                OPTIONAL MATCH comments = (user)-[:CREATED]->(comment:Comment)
                OPTIONAL MATCH causes = (user)-[:CREATED]->(cause:Causes)
                WITH project,parent,r,child,len,date,accomplish,comments,causes ORDER BY comment
                RETURN project,parent,r,collect(child),length(len),date,accomplish,collect(DISTINCT comments) AS comments, collect(DISTINCT causes) AS causes
                ORDER BY r
                CYPHER,
            [
                'user_email' => $user_email,
            ]
        );

        return $userHasProjetAndTodo;
    }
}
