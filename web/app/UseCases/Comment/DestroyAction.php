<?php

namespace App\UseCases\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class DestroyAction
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
     * Repositoryを介してDBからコメントを削除する
     *
     * @param array $comment
     */
    public function invoke(array $comment)
    {
        $this->comment_repository->destroyComment($comment);
    }
}
