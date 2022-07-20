<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use \Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
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
     * ログインしているか否か
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authStatus(Request $request)
    {
        if ($request->user()) {
            return response()->json(new UserResource($request->user()), Response::HTTP_OK);
        }
        return response()->json(['message' => 'ログインしていません。'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * ユーザーのログイン
     *
     * @param App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $onboarding = $this->user_repository->whetherExecuteOnboarding($request->user()->email);
            $user = new UserResource($request->user());
            $response = [
                'user' => $user,
                'onboarding' => $onboarding ? true : false
            ];
            return response()->json($response, Response::HTTP_OK);
        }

        return response()->json(['errors' => 'ユーザーが見つかりませんでした。'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * ユーザーのログアウト
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $user = $request->user();

        return response()->json(['message' => 'Logged out.', 'user' => $user], Response::HTTP_OK);
    }
}
