<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use packages\Domain\User\User;
use \Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
{

    public function authStatus(Request $request)
    {
        return $request->user() ? response()->json(new UserResource($request->user()), Response::HTTP_OK) : null;
    }

    public function login(Request $request)
    {
        //バリデーション
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(new UserResource($request->user()), Response::HTTP_OK);
        }

        return response()->json('Can Not Login.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $user = $request->user();

        return response()->json(['message' => 'Logged out.', 'user' => $user], Response::HTTP_OK);
    }
}
