<?php

namespace App\UseCases\Accomplish;

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
     * Repository介してDBで削除処理
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->todo_repository->destroyAccomplish($todo);
    }
}
