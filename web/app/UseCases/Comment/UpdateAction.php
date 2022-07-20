<?php

namespace App\UseCases\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class UpdateAction
{
    protected $comment_repository;

    /**
     * @param App\Repositories\Comment\CommentRepositoryInterface $commentRepositoryInterface
     */
    public function __construct(CommentRepositoryInterface $commentRepositoryInterface)
    {
        $this->comment_repository = $commentRepositoryInterface;
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
