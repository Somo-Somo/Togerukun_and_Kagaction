<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LineUsersQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string,integer,string>
     */
    protected $fillable = ['line_user_id', 'question_number', 'parent_uuid'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'question_number' => 'integer'
    ];

    // question_numberに対するクラス定数
    const NO_QUESTION = 0;
    const PROJECT = 1;
    const TODO = 2;
    const DATE = 3;
}
