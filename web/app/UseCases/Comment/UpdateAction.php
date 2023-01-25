<?php

namespace App\UseCases\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class UpdateAction
{
    protected $comment_repository;

    /**
     * @param App\Repositories\Comment\CommentRepositoryInterface $comment_repository_interface
     */
    public function __construct(CommentRepositoryInterface $comment_repository_interface)
    {
        $this->comment_repository = $comment_repository_interface;
    }

    /**
     * Repository介してDBからコメントを更新する
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->comment_repository->updateComment($todo);
    }
}
