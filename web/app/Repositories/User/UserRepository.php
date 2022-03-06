<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Neo4jDB;
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
        $this->client->run(
            <<<'CYPHER'
                CREATE (
                    :User {
                        user_id: $user_id,
                        name: $name, 
                        email: $email,
                        password: $password
                    })
                CYPHER,
                [
                    'user_id' => $user['id'], 
                    'name' => $user['name'], 
                    'email' => $user['email'],
                    'password' => $user['password']
                ]);
    }

    public function getUserHasProjetAndHypothesis(string $user_email)
    {
        $userHasProjetAndHypothesis = $this->client->run(
            <<<'CYPHER'
                MATCH len = (user:User{email:$user_email}) - [:HAS] -> (project:Project) <- [*] - (parent:Hypothesis)
                OPTIONAL MATCH (parent)<-[]-(child:Hypothesis)
                RETURN project.uuid,parent,collect(child),length(len)
                CYPHER,
                [
                    'user_email' => $user_email,
                ]
            );

        return $userHasProjetAndHypothesis;
    }
}
