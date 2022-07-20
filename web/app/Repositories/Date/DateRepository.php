<?php

namespace App\Repositories\Date;

use App\Facades\Neo4jDB;
use App\Repositories\Date\DateRepositoryInterface;

class DateRepository implements DateRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    /**
     * 日付が設定してあるTodoをDBから取得
     *
     * @param string $user_email
     * @return $todo_and_schedule
     */
    public function getDate(string $user_email)
    {
        $todo_and_schedule = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [date:DATE] -> (todo:Todo),
                len = (project:Project) <- [r*] - (todo)
                OPTIONAL MATCH (user) - [accomplished:ACCOMPLISHED] -> (todo)
                OPTIONAL MATCH (todo) - [:TO_ACHIEVE] -> (parent:Todo)
                OPTIONAL MATCH (todo) <- [:TO_ACHIEVE] - (child:Todo)
                OPTIONAL MATCH (parent)<-[:TO]-(comment:Comment)
                OPTIONAL MATCH comments = (:User)-[:CREATED]->(comment:Comment)
                WITH project,todo,parent,child,len,date,accomplished,comments ORDER BY comment
                RETURN project, todo, accomplished, date, parent, length(len), collect(child), collect(DISTINCT comments) AS comments
                ORDER BY date.on ASC
                CYPHER,
            [
                'user_email' => $user_email,
            ]
        );
        return $todo_and_schedule;
    }

    /**
     * 日付をDB上で更新
     *
     * @param array $todo
     */
    public function updateDate(array $todo)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (todo:Todo { uuid: $uuid })
                OPTIONAL MATCH x = (user) - [date:DATE] -> (todo)
                WHERE x IS NOT NULL
                SET date.on = $date
                WITH user, todo, x
                WHERE x IS NULL
                CREATE (user) - [
                    :DATE { on: $date }
                ] -> (todo)
                RETURN todo
                CYPHER,
            [
                'uuid' => $todo['uuid'],
                'user_email' => $todo['user_email'],
                'date' => $todo['date']
            ]
        );
    }

    /**
     * 選択されたTodoの日付の値からDB上で削除
     *
     * @param array $todo
     */
    public function destroyDate(array $todo)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) -
                [date: DATE]
                ->(todo:Todo { uuid: $uuid })
                DELETE date
                RETURN todo
                CYPHER,
            [
                'uuid' => $todo['uuid'],
                'user_email' => $todo['user_email']
            ]
        );
    }
}
