<?php

namespace App\UseCases\Todo;

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
     * Repositoryを介してDBからTodoを削除
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->todo_repository->destroy($todo);
    }
}
