<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class Onboarding extends Controller
{
    protected $user_repository;

    /**
     * @param App\Repositories\User\UserRepositoryInterface $user_repository_interface
     */
    public function __construct(UserRepositoryInterface $user_repository_interface)
    {
        $this->user_repository = $user_repository_interface;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $onboarding = [
            'project' => [
                'name' => $request->projectName,
                'uuid' => (string) Str::uuid()
            ],
            'goal' => [
                'name' => $request->goalName,
                'uuid' => (string) Str::uuid(),
                'date' => $request->goalDate
            ],
            'created_by_user_email' => $request->user()->email
        ];
        $this->user_repository->finishedOnboarding($onboarding);
        $json = [
            'message' => 'オンボーディングを完了しました',
            'error' => '',
        ];
        return response()->json($json, Response::HTTP_OK);
    }
}
