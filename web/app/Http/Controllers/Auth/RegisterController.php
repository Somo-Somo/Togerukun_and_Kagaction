<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\RegisterRequest;
use App\UseCases\Initialize\Template\GenerateAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    protected $user_repository;

    public function __construct(UserRepositoryInterface $user_repository_interface)
    {
        $this->user_repository = $user_repository_interface;
    }

    /**
     * ユーザーの会員登録
     *
     * @param App\Http\Requests\RegisterRequest $request 会員登録のバリデーション
     * @param App\UseCases\Initialize\Template\GenerateAction $generateAction 会員登録後プロジェクトのテンプレートを生成
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request, GenerateAction $generateAction)
    {
        //バリエーションで問題がなかった場合にはユーザを作成する。
        $user = User::create([
            'name' => $request->name,
            'uuid' => (string) Str::uuid(),
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        // ユーザー作成後UserRepositoryを通してNeo4jに保存
        $create_user = $this->user_repository->register($user);

        if ($create_user) {
            $generateAction->invoke($user->uuid);
        }

        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }
}
