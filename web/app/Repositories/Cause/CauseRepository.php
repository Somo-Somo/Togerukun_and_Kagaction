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

    /**
     * 原因コメントをneo4jのDBに保存
     *
     * @param array $cause Todoの原因コメント
     */
    public function storeCause(array $cause)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [:CREATED] -> (todo:Todo {uuid: $todo_uuid})
                CREATE (cause:Cause{
                    uuid: $cause_uuid,
                    text: $text
                }) - [
                    :IS_THE_CAUSE_OF{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ] -> (todo)
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
    }

    /**
     * 原因コメントをneo4jのDBから削除
     *
     * @param array $cause
     */
    public function destroyCause(array $cause)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [created:CREATED] -> (cause:Cause { uuid : $cause_uuid }),
                (cause) <- [is_the_cause_of:IS_THE_CAUSE_OF] - (todo:Todo)
                DELETE is_the_cause_of, created
                DELETE cause
                RETURN user
                CYPHER,
            [
                'user_email' => $cause['user_email'],
                'cause_uuid' => $cause['cause_uuid']
            ]
        );
    }
}
