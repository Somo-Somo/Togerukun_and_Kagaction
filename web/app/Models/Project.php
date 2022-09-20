<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_uuid',
        'name',
        'uuid',
        'created_at'
    ];

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
