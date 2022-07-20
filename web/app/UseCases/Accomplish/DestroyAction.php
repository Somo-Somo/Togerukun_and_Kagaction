<?php

namespace App\UseCases\Accomplish;

use App\Repositories\Todo\TodoRepositoryInterface;

class DestroyAction
{
    protected $todo_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface $todoRepositoryInterface
     */
    public function __construct(TodoRepositoryInterface $todoRepositoryInterface)
    {
        $this->todo_repository = $todoRepositoryInterface;
    }

    /**
     * Repository介してDBで削除処理
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->todo_repository->destroyAccomplish($todo);
    }
}
