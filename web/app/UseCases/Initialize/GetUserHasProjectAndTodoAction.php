<?php

namespace App\UseCases\Initialize;

use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\Todo\Converter\TodoListConverter;
use App\UseCases\Project\Converter\ProjectListConverter;

class GetUserHasProjectAndTodoAction
{
    protected $user_repository;
    protected $todoListConverter;
    protected $projectListConverter;

    public function __construct(UserRepositoryInterface $userRepositoryInterface, TodoListConverter $todoListConverter, ProjectListConverter $projectListConverter)
    {
        $this->user_repository = $userRepositoryInterface;
        $this->todoListConverter = $todoListConverter;
        $this->projectListConverter = $projectListConverter;
    }

    public function invoke($userEmail)
    {
        $fetchProjectAndTodoFromNeo4j = $this->user_repository->getUserHasProjetAndTodo($userEmail);
        $todoList = $this->todoListConverter->invoke($fetchProjectAndTodoFromNeo4j);
        return $todoList;
    }
}