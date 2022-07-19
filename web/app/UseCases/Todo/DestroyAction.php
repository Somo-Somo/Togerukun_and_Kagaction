<?php

namespace App\UseCases\Todo;

use App\Repositories\Todo\TodoRepositoryInterface;

class DestroyAction
{
    protected $todo_repository;

    public function __construct(TodoRepositoryInterface $todoRepositoryInterface)
    {
        $this->todo_repository = $todoRepositoryInterface;
    }

    public function invoke(array $todo)
    {
        $this->todo_repository->destroy($todo);

        return;
    }
}
