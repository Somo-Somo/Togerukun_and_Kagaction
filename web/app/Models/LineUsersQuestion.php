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
     * @var array<string>
     */
    protected $fillable = ['line_user_id', 'question_number'];


    // question_numberに対するクラス定数
    const PROJECT = 0;
    const TODO = 1;
    const DATE = 2;
}
