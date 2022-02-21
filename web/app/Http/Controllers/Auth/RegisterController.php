<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use packages\Domain\User\User;
use packages\Domain\User\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    protected $user_repository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->user_repository = $userRepositoryInterface;
    }

    public function register(Request $request)
    {
        //入力バリデーション
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        //バリデーションで問題があった際にはエラーを返す。
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //バリエーションで問題がなかった場合にはユーザを作成する。
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ユーザー作成後UserRepositoryを通してNeo4jに保存
        $this->user_repository->register($user);

        //ユーザの作成が完了するとjsonを返す
        $json = [
            'data' => $user,
            'message' => 'User registration success!',
            'error' => ''
        ];

 
        return response()->json( $json, Response::HTTP_OK);
    }
}
