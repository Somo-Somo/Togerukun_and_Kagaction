<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\RegisterRequest;
use App\UseCases\HowToKagaction\StoreAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    protected $user_repository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->user_repository = $userRepositoryInterface;
    }

    public function register(RegisterRequest $request, StoreAction $storeAction)
    {
        //バリエーションで問題がなかった場合にはユーザを作成する。
        $user = User::create([
            'name' => $request->name,
            'uuid' => (string) Str::uuid(),
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // ユーザー作成後UserRepositoryを通してNeo4jに保存
        $createUser = $this->user_repository->register($user);

        if ($createUser) {
            $storeAction->invoke($user->email);
        }

 
        return response()->json( new UserResource($user), Response::HTTP_CREATED);
    }
}
