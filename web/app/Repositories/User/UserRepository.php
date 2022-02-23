<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Neo4jDB;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function register($user)
    {
        $client = Neo4jDB::call();
        $client->run(
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
}
