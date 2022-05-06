<?php

namespace App\UseCases\Todo;

use App\Repositories\Todo\TodoRepositoryInterface;
use App\UseCases\Todo\Converter\TodoListConverter;
use App\UseCases\Project\Converter\ProjectListConverter;

class StoreAction
{
    protected $todo_repository;
    protected $todoListConverter;
    protected $projectListConverter;

    public function __construct(TodoRepositoryInterface $todoRepositoryInterface, TodoListConverter $todoListConverter, ProjectListConverter $projectListConverter)
    {
        $this->todo_repository = $todoRepositoryInterface;
        $this->todoListConverter = $todoListConverter;
        $this->projectListConverter = $projectListConverter;
    }

    public function invoke(array $todo)
    {

        $this->todo_repository->create($todo);
        
        return;
    }
}