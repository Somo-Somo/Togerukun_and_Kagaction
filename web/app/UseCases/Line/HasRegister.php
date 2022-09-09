<?php

namespace App\UseCases\Line;

use App\Models\User;

class HasRegister
{

    public function __construct()
    {
    }

    /**
     * ユーザーが会員登録されているか確認する
     *
     * @param
     * @return
     */
    public function invoke($userId)
    {
        // userIdがあるユーザーを検索
        $user = User::where('line_user_id', $userId)->first();

        return $user;
    }
}
