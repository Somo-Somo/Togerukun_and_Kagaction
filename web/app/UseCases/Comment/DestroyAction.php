<?php

namespace App\UseCases\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class DestroyAction
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
     * Repositoryを介してDBからコメントを削除する
     *
     * @param array $comment
     */
    public function invoke(array $comment)
    {
        $this->comment_repository->destroyComment($comment);
    }
}
