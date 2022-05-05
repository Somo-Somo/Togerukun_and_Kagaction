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

    public function storeComment(array $comment)
    {
        $comments = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [:CREATED] -> (hypothesis:Hypothesis {uuid: $hypothesis_uuid})
                CREATE (comment:Comment{
                    uuid: $comment_uuid,
                    text: $text
                }) - [
                    to:TO{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ] -> (hypothesis)
                CREATE (user) - [created:CREATED{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (comment)
                RETURN user, hypothesis, comment
                CYPHER,
                [
                    'user_email' => $comment['user_email'],
                    'hypothesis_uuid' => $comment['hypothesis_uuid'],
                    'comment_uuid' => $comment['comment_uuid'],
                    'text' => $comment['text']
                ]
        );
        return $comments;
    }

    public function updateComment(array $comment)
    {
        $updateHypothesisComment = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (comment:Comment { uuid: $uuid })
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
                    'user_email' => $comment['user_email'],
                    'uuid' => $comment['uuid'],
                    'text' => $comment['text']
                ]
            );
        return;
    }

    public function destroyComment(array $comment)
    {
        $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [created:CREATED] -> (comment:Comment { uuid : $comment_uuid }),
                (comment) - [to: TO] -> (hypothesis:Hypothesis)
                DELETE to, created
                DELETE comment
                RETURN user
                CYPHER,
                [
                    'user_email' => $comment['user_email'],
                    'comment_uuid' => $comment['comment_uuid']
                ]
            );
        return;
    }
}
