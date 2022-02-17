<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use packages\Domain\User\User;
use \Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        //バリデーション
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //login処理
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            $user->tokens()->delete();
            $token = $user->createToken("login:user{$user->id}")->plainTextToken;

            //ログインが成功するとtokenを返す。
            return response()->json(['token' => $token], Response::HTTP_OK);
        }

        return response()->json('Can Not Login.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // session refresh
        $request->session()->invalidate();

        // regenerate token
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out.'], Response::HTTP_OK);
    }
}
