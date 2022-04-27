<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function storeComment(array $hypothesis);
    public function updateComment(array $comment);
    public function destroyComment(array $hypothesis);
}