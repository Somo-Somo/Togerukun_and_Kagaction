<?php

namespace App\UseCases\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class UpdateAction
{
    protected $comment_repository;

    public function __construct(CommentRepositoryInterface $commentRepositoryInterface)
    {
        $this->comment_repository = $commentRepositoryInterface;
    }

    public function invoke(array $todo)
    {

        $this->comment_repository->updateComment($todo);
        
        return; 
    }
}