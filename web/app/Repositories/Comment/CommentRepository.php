<?php

namespace App\Repositories\Comment;

use App\Facades\Neo4jDB;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    /**
     * コメントをDBに保存する
     *
     * @param array $comment
     */
    public function storeComment(array $comment)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { uuid : $user_uuid }) - [:CREATED] -> (todo:Todo {uuid: $todo_uuid})
                CREATE (comment:Comment{
                    uuid: $comment_uuid,
                    text: $text
                }) - [
                    to:TO{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ] -> (todo)
                CREATE (user) - [created:CREATED{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (comment)
                RETURN user, todo, comment
                CYPHER,
            [
                'user_uuid' => $comment['user_uuid'],
                'todo_uuid' => $comment['todo_uuid'],
                'comment_uuid' => $comment['comment_uuid'],
                'text' => $comment['text']
            ]
        );
    }

    /**
     * DBにあるコメントを更新する
     *
     * @param array $comment
     */
    public function updateComment(array $comment)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { uuid : $user_uuid }), (comment:Comment { uuid: $uuid })
                SET comment.text = $text
                WITH user,comment
                OPTIONAL MATCH x = (user)-[updated:UPDATED]->(comment)
                WHERE x IS NOT NULL
                SET updated.at = localdatetime({timezone: 'Asia/Tokyo'})
                WITH user, comment, x
                WHERE x IS NULL
                CREATE (user)-[:UPDATED{at:localdatetime({timezone: 'Asia/Tokyo'})}]->(comment)
                RETURN comment
                CYPHER,
            [
                'user_uuid' => $comment['user_uuid'],
                'uuid' => $comment['uuid'],
                'text' => $comment['text']
            ]
        );
    }

    /**
     * コメントをDBから削除する
     *
     * @param array $comment
     */
    public function destroyComment(array $comment)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { uuid : $user_uuid }) - [created:CREATED] -> (comment:Comment { uuid : $comment_uuid }),
                (comment) - [to: TO] -> (todo:Todo)
                DELETE to, created
                DELETE comment
                RETURN user
                CYPHER,
            [
                'user_uuid' => $comment['user_uuid'],
                'comment_uuid' => $comment['comment_uuid']
            ]
        );
    }
}
