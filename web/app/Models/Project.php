<?php

namespace App\Models;

class Project
{
    /**
     * プロジェクトを確認する
     *
     * @param string $user_name
     * @return string $reply_message
     */
    public static function confirmProject(string $project_name)
    {
        return  '「' . $project_name . '」だね！';
    }
}
