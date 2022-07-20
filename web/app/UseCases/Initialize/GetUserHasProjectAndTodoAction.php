<?php

namespace App\UseCases\Initialize;

use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\Todo\Converter\TodoListConverter;

class GetUserHasProjectAndTodoAction
{
    protected $user_repository;
    protected $todo_list_converter;

    /**
     * @param App\Repositories\User\UserRepositoryInterface $user_repository_interface
     * @param App\UseCases\Todo\Converter\TodoListConverter $todo_list_converter
     */
    public function __construct(UserRepositoryInterface $user_repository_interface, TodoListConverter $todo_list_converter)
    {
        $this->user_repository = $user_repository_interface;
        $this->todo_list_converter = $todo_list_converter;
    }

    /**
     * ユーザーのプロジェクトとTodoをDBからfetch
     * Todo一覧に形を変換
     *
     * @param string $user_email
     * @return array $todo_list
     */
    public function invoke(string $userEmail)
    {
        $fetch_project_and_todo_from_db = $this->user_repository->getUserHasProjetAndTodo($userEmail);
        $todo_list = $this->todo_list_converter->invoke($fetch_project_and_todo_from_db);
        return $todo_list;
    }
}
