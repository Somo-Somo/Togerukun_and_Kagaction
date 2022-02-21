<?php

namespace packages\Infrastructure\Repositories\User;

use Illuminate\Support\Facades\Neo4jDB;
use packages\Domain\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function register($user)
    {
        $client = Neo4jDB::call();
        $client->run(
            <<<'CYPHER'
                CREATE (u:User {name:"test1"})
                CYPHER,);
    }
}
