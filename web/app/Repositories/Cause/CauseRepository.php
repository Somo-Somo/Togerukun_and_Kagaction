<?php

namespace App\Repositories\Cause;

use App\Facades\Neo4jDB;
use App\Repositories\Cause\CauseRepositoryInterface;

class CauseRepository implements CauseRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function storeCause(array $cause)
    {
        $causes = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [:CREATED] -> (todo:Todo {uuid: $todo_uuid})
                CREATE (cause:Cause{
                    uuid: $cause_uuid,
                    text: $text
                }) <- [
                    unavailable_cause:UNAVAILABLE_CAUSE{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ] - (todo)
                CREATE (user) - [created:CREATED{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (cause)
                RETURN user, todo, cause
                CYPHER,
            [
                'user_email' => $cause['user_email'],
                'todo_uuid' => $cause['todo_uuid'],
                'cause_uuid' => $cause['cause_uuid'],
                'text' => $cause['text']
            ]
        );
        return $causes;
    }

    public function destroyCause(array $cause)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [created:CREATED] -> (cause:Cause { uuid : $cause_uuid }),
                (cause) <- [unavailable_cause:UNAVAILABLE_CAUSE] - (todo:Todo)
                DELETE unavailable_cause, created
                DELETE cause
                RETURN user
                CYPHER,
            [
                'user_email' => $cause['user_email'],
                'cause_uuid' => $cause['cause_uuid']
            ]
        );
        return;
    }
}
