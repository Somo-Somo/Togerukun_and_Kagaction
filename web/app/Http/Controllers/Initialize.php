<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\Initialize\GetUserHasProjectAndTodoAction;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;


class Initialize extends Controller
{
    protected $user_repository;

    /**
     * @param App\Repositories\User\UserRepositoryInterface $userRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->user_repository = $userRepositoryInterface;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * // ↓別のPRで関数名変える
     * @param  \App\UseCases\Initialize\GetUserHasProjectAndTodoAction $get_user_has_project_and_todo_action ユーザーのTodoを全てを取得する処理
     * @param \App\UseCases\Project\IndexAction $project_index_action ユーザーのプロジェクトを全て取得する
     * @param \App\UseCases\Date\IndexAction $date_index_action ユーザーの日付がついているTodo全てを取得する
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request,
        GetUserHasProjectAndTodoAction $get_user_has_project_and_todo_action,
        \App\UseCases\Project\IndexAction $project_index_action,
        \App\UseCases\Date\IndexAction $date_index_action,
    ) {
        $todo_list = $get_user_has_project_and_todo_action->invoke($request->user()->id);
        $project_list = $project_index_action->invoke($request->user()->id);
        $schedule_list = $date_index_action->invoke($request->user()->id);
        $onboarding = $this->user_repository->whetherExecuteOnboarding($request->user()->id);

        $user_has_project_and_todo = [
            'project' => $project_list,
            'todo' => $todo_list,
            'schedule' => $schedule_list,
            'onboarding' => $onboarding ? true : false
        ];

        return response()->json($user_has_project_and_todo, Response::HTTP_OK);
    }
}
