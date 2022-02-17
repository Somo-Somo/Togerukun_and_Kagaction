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
            $user = User::whereEmail($request->email)->first(); //トークンの作成と取得

            $user->tokens()->delete();
            $token = $user->createToken("login:user{$user->id}")->plainTextToken;

            //ログインが成功するとtokenを返す。
            return response()->json(['token' => $token], Response::HTTP_OK);
        }

        return response()->json('Can Not Login.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
