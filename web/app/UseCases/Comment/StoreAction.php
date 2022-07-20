<?php

namespace App\UseCases\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class StoreAction
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
     * Repository介してDBからコメントを保存する
     *
     * @param array $comment
     */
    public function invoke(array $comment)
    {
        $this->comment_repository->storeComment($comment);
    }
}
