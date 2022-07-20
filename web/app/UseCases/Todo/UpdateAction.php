<?php

namespace App\UseCases\Todo;

use App\Repositories\Todo\TodoRepositoryInterface;

class UpdateAction
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
     * Repository介してDBにあるTodoを更新
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->todo_repository->update($todo);
    }
}
