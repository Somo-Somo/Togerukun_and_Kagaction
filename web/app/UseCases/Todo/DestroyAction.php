<?php

namespace App\UseCases\Todo;

use App\Repositories\Todo\TodoRepositoryInterface;

class DestroyAction
{
    protected $todo_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface $todo_repository_interface
     */
    public function __construct(TodoRepositoryInterface $todo_repository_interface)
    {
        $this->todo_repository = $todo_repository_interface;
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
