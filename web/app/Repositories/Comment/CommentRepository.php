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
        $todoAndSchedule = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - [date:DATE] -> (hypothesis:Hypothesis),
                len = (project:Project) <- [r*] - (hypothesis)
                OPTIONAL MATCH (user) - [accomplished:ACCOMPLISHED] -> (hypothesis)
                OPTIONAL MATCH (hypothesis) - [:TO_ACHIEVE] -> (parent:Hypothesis)
                OPTIONAL MATCH (hypothesis) <- [:TO_ACHIEVE] - (child:Hypothesis)
                RETURN project, hypothesis, accomplished, date, parent, length(len), child
                ORDER BY date.on ASC
                CYPHER,
                [
                    
                ]
        );
        return $todoAndSchedule;
    }

    public function updateComment(array $hypothesis)
    {
        $updateHypothesisComment = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }), (hypothesis:Hypothesis { uuid: $uuid })
                OPTIONAL MATCH x = (user) - [date:DATE] -> (hypothesis)
                WHERE x IS NOT NULL
                SET date.on = $date
                WITH user, hypothesis, x
                WHERE x IS NULL
                CREATE (user) - [
                    :DATE { on: $date }
                ] -> (hypothesis)
                RETURN hypothesis
                CYPHER,
                [
                    'uuid' => $hypothesis['uuid'], 
                    'user_email' => $hypothesis['user_email'], 
                    'date' => $hypothesis['date']
                ]
            );
        return;
    }

    public function destroyComment(array $hypothesis)
    {
        $deleteHypothesisComment = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User { email : $user_email }) - 
                [date: DATE]
                ->(hypothesis:Hypothesis { uuid: $uuid })
                DELETE date
                RETURN hypothesis
                CYPHER,
                [
                    'uuid' => $hypothesis['uuid'], 
                    'user_email' => $hypothesis['user_email']
                ]
            );
        return;
    }
}
