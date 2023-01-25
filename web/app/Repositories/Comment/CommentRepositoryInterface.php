<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function storeComment(array $comment);
    public function updateComment(array $comment);
    public function destroyComment(array $comment);
}