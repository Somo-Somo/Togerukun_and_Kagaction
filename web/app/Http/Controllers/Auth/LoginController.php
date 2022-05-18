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

    public function authStatus(Request $request)
    {
        if ($request->user()) {
            return response()->json(new UserResource($request->user()), Response::HTTP_OK);
        }
        return response()->json(['message' => 'ログインしていません。'], Response::HTTP_UNAUTHORIZED);
    }

    public function login(LoginRequest $request, UserRepositoryInterface $userRepositoryInterface)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $onboarding = $userRepositoryInterface->whetherExecuteOnboarding($request->user()->email);
            $user = new UserResource($request->user());
            $user['onboarding'] = $onboarding;
            return response()->json($user, Response::HTTP_OK);
        }

        return response()->json(['errors' => 'ユーザーが見つかりませんでした。'], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $user = $request->user();

        return response()->json(['message' => 'Logged out.', 'user' => $user], Response::HTTP_OK);
    }
}
