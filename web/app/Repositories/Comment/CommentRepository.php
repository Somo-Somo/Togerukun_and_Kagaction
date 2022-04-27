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

    public function storeComment(array $hypothesis)
    {
        $comment = $this->client->run(
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
                    'user_email' => $hypothesis['user_email'],
                    'hypothesis_uuid' => $hypothesis['comment_uuid'],
                    'comment_uuid' => $hypothesis['comment_uuid'],
                    'text' => $hypothesis['comment']
                ]
        );
        return $comment;
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
                    'text' => $comment['comment']
                ]
            );
        return;
    }

    public function destroyComment(array $hypothesis)
    {
        $deleteHypothesisComment = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }),
                (comment:Comment { uuid : $comment_uuid }) - [to: TO] -> (hypothesis:Hypothesis { uuid: $hypothesis_uuid })
                CREATE (user) - [
                    :DELETED{at:localdatetime({timezone: 'Asia/Tokyo'})}
                ] -> (comment)
                DELETE to
                RETURN user, comment
                CYPHER,
                [
                    'user_email' => $hypothesis['user_email'],
                    'hypothesis_uuid' => $hypothesis['comment_uuid'],
                    'comment_uuid' => $hypothesis['comment_uuid']
                ]
            );
        return;
    }
}
