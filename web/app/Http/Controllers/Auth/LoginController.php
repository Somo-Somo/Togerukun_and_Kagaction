<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use \Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
{

    public function authStatus(Request $request)
    {
        return $request->user() ? response()->json(new UserResource($request->user()), Response::HTTP_OK) : null;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(new UserResource($request->user()), Response::HTTP_OK);
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
