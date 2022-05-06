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

    public function getDate(string $user_email)
    {
        $todoAndSchedule = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [date:DATE] -> (todo:Todo),
                len = (project:Project) <- [r*] - (todo)
                OPTIONAL MATCH (user) - [accomplished:ACCOMPLISHED] -> (todo)
                OPTIONAL MATCH (todo) - [:TO_ACHIEVE] -> (parent:Todo)
                OPTIONAL MATCH (todo) <- [:TO_ACHIEVE] - (child:Todo)
                RETURN project, todo, accomplished, date, parent, length(len), collect(child)
                ORDER BY date.on ASC
                CYPHER,
                [
                    'user_email' => $user_email, 
                ]
        );
        return $todoAndSchedule;
    }

    public function updateDate(array $todo)
    {
        $updateTodoDate = $this->client->run(
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
        return;
    }

    public function destroyDate(array $todo)
    {
        $deleteTodoDate = $this->client->run(
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
        return;
    }
}
