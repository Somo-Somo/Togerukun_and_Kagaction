<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    /**
     * プロジェクトのゴールを聞く
     *
     * @param string $user_name
     * @param string $project_name
     * @return string $reply_message
     */
    public static function askGoal(string $user_name, string $project_name)
    {
        $reply_message =
            'ありがとうございます！' . "\n" . $user_name . 'さんが' . $project_name . 'で達成したいゴールまたは目標を教えてください！';
        return $reply_message;
    }
}
