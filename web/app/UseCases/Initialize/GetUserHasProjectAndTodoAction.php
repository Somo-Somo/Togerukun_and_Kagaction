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

    /**
     * @param App\Repositories\User\UserRepositoryInterface $userRepositoryInterface
     * @param App\UseCases\Todo\Converter\TodoListConverter $todoListConverter
     * @param App\UseCases\Project\Converter\ProjectListConverter $projectListConverter
     */
    public function __construct(UserRepositoryInterface $userRepositoryInterface, TodoListConverter $todoListConverter, ProjectListConverter $projectListConverter)
    {
        $this->user_repository = $userRepositoryInterface;
        $this->todoListConverter = $todoListConverter;
        $this->projectListConverter = $projectListConverter;
    }

    /**
     * ユーザーのプロジェクトとTodoをDBからfetch
     * Todo一覧に形を変換
     *
     * @param string $user_email
     * @return array $todoList
     */
    public function invoke(string $userEmail)
    {
        $fetchProjectAndTodoFromDB = $this->user_repository->getUserHasProjetAndTodo($userEmail);
        $todoList = $this->todoListConverter->invoke($fetchProjectAndTodoFromDB);
        return $todoList;
    }
}
