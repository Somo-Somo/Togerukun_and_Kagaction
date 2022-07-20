<?php

namespace App\UseCases\Accomplish;

use App\Repositories\Todo\TodoRepositoryInterface;

class UpdateAction
{
    protected $todo_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface
     */
    public function __construct(TodoRepositoryInterface $todoRepositoryInterface)
    {
        $this->todo_repository = $todoRepositoryInterface;
    }

    /**
     * Repository介してDBの完了の値を更新
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->todo_repository->updateAccomplish($todo);
    }
}
