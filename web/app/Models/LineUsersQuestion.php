<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LineUsersQuestion extends Model
{
    use HasFactory;

    // question_numberに対するクラス定数
    const NO_QUESTION = 0;
    const PROJECT = 10;
    const GOAL = 20;
    const TODO = 30;
    const DATE = 40;
    const RENAME_TODO = 31;
    const TODO_LIST = [
        'ALL_TODO_LIST' => true,
        'WEEKLY_TODO_LIST' => true
    ];
    const ADD_TODO = 'ADD_TODO';
    const LIMIT_DATE = 'LIMIT_DATE';
    const CHANGE_TODO = 'CHANGE_TODO';
    const DELETE_TODO = [
        'DELETE_TODO' => true,
        'OK_DELETE_TODO' => true,
        'NOT_DELETE_TODO' => true
    ];
    const CHANGE_DATE = [
        'ASK_RESCHEDULE' => true,
        'RESCHEDULE' => true,
    ];
    const CHECK_TODO = [
        'SELECT_CHECK_TODO' => true,
        'CHECK_TODO_BY_TODAY' => true,
        'CHECK_TODO_BY_THIS_WEEK' => true,
        'SELECT_TODO_LIST_TO_CHECK' => true,
        'CHECK_TODO' => true,
        'ACCOMPLISHED_TODO' => true,
        'NOT_ACCOMPLISHED_TODO' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string,integer,string>
     */
    protected $fillable = ['line_user_id', 'question_number', 'parent_uuid', 'project_uuid'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'question_number' => 'integer'
    ];

    /**
     * questionに紐づくプロジェクト
     *
     */
    public function project()
    {
        return $this->hasOne(Project::class, 'uuid', 'project_uuid');
    }
}
