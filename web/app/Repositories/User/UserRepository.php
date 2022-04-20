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
                CREATE (
                    user:User {
                        user_id: $user_id,
                        name: $name, 
                        uuid: $uuid, 
                        email: $email,
                        password: $password,
                        created_at: localdatetime({timezone: 'Asia/Tokyo'})
                    })
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

    public function getUserHasProjetAndHypothesis(string $user_email)
    {
        $userHasProjetAndHypothesis = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User{email:$user_email}) - [:HAS] -> (project:Project),
                    len = (project) <- [r*] - (parent:Hypothesis)
                OPTIONAL MATCH (parent)<-[]-(child:Hypothesis)
                OPTIONAL MATCH (:User)-[currentGoal:SET_CURRENT_GOAL]->(parent)
                OPTIONAL MATCH (:User)-[accomplish:ACCOMPLISHED]->(parent)
                RETURN project,parent,r,collect(child),length(len),currentGoal,accomplish
                ORDER BY r
                CYPHER,
                [
                    'user_email' => $user_email,
                ]
            );

        return $userHasProjetAndHypothesis;
    }
}
