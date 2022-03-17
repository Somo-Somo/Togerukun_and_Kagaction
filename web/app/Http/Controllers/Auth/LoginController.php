<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;


class LoginController extends Controller
{

    public function authStatus(Request $request)
    {
        $enviroment = App::environment();
        if ($request->user()) {
            return response()->json(new UserResource($request->user()), Response::HTTP_OK);
        }
        return response()->json(['message' => 'ログインしていません。'], Response::HTTP_UNAUTHORIZED);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $config = [
            'env' => config('app.env'),
            'username' => config('database.connections.neo4j.username'),
            'password' => config('database.connections.neo4j.password'),
            'pgsql' => config('database.connections.pgsql'),
            'defalut' => config('database.defalut'),
            'sanctum' => config('sanctum.stateful'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(new UserResource($request->user()), Response::HTTP_OK);
        }

        return response()->json(['errors' => 'ユーザーが見つかりませんでした。','config' => $config], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $user = $request->user();

        return response()->json(['message' => 'Logged out.', 'user' => $user], Response::HTTP_OK);
    }
}
