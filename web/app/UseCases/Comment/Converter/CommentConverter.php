<?php

namespace App\UseCases\Comment\Converter;

use DateTime;

class CommentConverter
{
    /**
     * Cypherで引っ張ってきたものをuser_uuidとuser_nameとcreated_atの連想配列の形に変換する
     *
     * @param array $fetchComments
     * @return array $comments
     */
    public function invoke(array $fetchComments)
    {
        $comments = [];

        foreach ($fetchComments as $key => $value) {
            $user = $value->getNodes()[0]->getProperties()->toArray();
            $comment = $value->getNodes()[1]->getProperties()->toArray();
            $unix = $value->getRelationships()[0]->getProperties()->toArray();
            $date = new DateTime('@' . $unix['at']['seconds']);
            $createdAt = $date->format('Y-m-d H:i:s');

            $comment['user_uuid'] = $user['uuid'];
            $comment['user_name'] = $user['name'];
            $comment['created_at'] = $createdAt;

            $comments[] = $comment;
        }

        return $comments;
    }
}
