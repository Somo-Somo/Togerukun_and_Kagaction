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
                ]);
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

    public function finishedOnboarding(string $user_email)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User{email:$user_email}) - [not_execute:NOT_EXECUTE] -> (:Onboarding)
                DELETE not_execute
                RETURN onboarding
                CYPHER,
                [
                    'user_email' => $user_email,
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
                OPTIONAL MATCH comments = (:User)-[:CREATED]->(comment:Comment)
                WITH project,parent,r,child,len,date,accomplish,comments ORDER BY comment
                RETURN project,parent,r,collect(child),length(len),date,accomplish,collect(DISTINCT comments) AS comments
                ORDER BY r
                CYPHER,
                [
                    'user_email' => $user_email,
                ]
            );

        return $userHasProjetAndTodo;
    }
}
