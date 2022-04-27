<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function storeComment(array $hypothesis);
    public function updateComment(array $hypothesis);
    public function destroyComment(array $hypothesis);
}