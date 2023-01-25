<?php

namespace App\UseCases\Comment\Converter;

use DateTime;

class CommentConverter
{
    /**
     * Cypherで引っ張ってきたものをuser_uuidとuser_nameとcreated_atの連想配列の形に変換する
     *
     * @param array $fetch_comments
     * @return array $comments
     */
    public function invoke(array $fetch_comments)
    {
        $comments = [];

        foreach ($fetch_comments as $key => $value) {
            $user = $value->getNodes()[0]->getProperties()->toArray();
            $comment = $value->getNodes()[1]->getProperties()->toArray();
            $unix = $value->getRelationships()[0]->getProperties()->toArray();
            $date = new DateTime('@' . $unix['at']['seconds']);
            $created_at = $date->format('Y-m-d H:i:s');

            $comment['user_uuid'] = $user['uuid'];
            $comment['user_name'] = $user['name'];
            $comment['created_at'] = $created_at;

            $comments[] = $comment;
        }

        return $comments;
    }
}
