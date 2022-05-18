<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class Onboarding extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->user_repository->finishedOnboarding($request->user()->email);
        $json = [
            'message' => 'オンボーディングを終了しました',
            'error' => '',
        ];
        return response()->json($json, Response::HTTP_OK);
    }
}
