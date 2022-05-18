<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\Initialize\GetUserHasProjectAndTodoAction;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;


class Initialize extends Controller
{
    protected $user_repository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->user_repository = $userRepositoryInterface;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UseCases\Initialize\GetUserHasProjectAndTodoAction  $getUserHasProjectAndTodoAction
     * @param \App\UseCases\Project\IndexAction $projectIndexAction
     * @param \App\UseCases\Date\IndexAction $dateIndexAction
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request, 
        GetUserHasProjectAndTodoAction $getUserHasProjectAndTodoAction,
        \App\UseCases\Project\IndexAction $projectIndexAction,
        \App\UseCases\Date\IndexAction $dateIndexAction,
    )
    {
        $todoList= $getUserHasProjectAndTodoAction->invoke($request->user()->email);
        $projectList= $projectIndexAction->invoke($request->user()->email);
        $scheduleList= $dateIndexAction->invoke($request->user()->email);
        $onboarding = $this->user_repository->whetherExecuteOnboarding($request->user()->email);

        $userHasProjectAndTodo = [
            'project' => $projectList,
            'todo' => $todoList,
            'schedule' => $scheduleList,
            'onboarding' => $onboarding ? true : false
        ];

        return response()->json($userHasProjectAndTodo, Response::HTTP_OK);
    }
}
