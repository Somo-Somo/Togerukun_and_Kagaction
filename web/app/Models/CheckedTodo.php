<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckedTodo extends Model
{
    use HasFactory;

    const CHECK_TODO = [
        'SELECT_CHECK_TODO' => true,
        'CHECK_TODO_BY_TODAY' => 51,
        'CHECK_TODO_BY_THIS_WEEK' => 52,
        'SELECT_TODO_LIST_TO_CHECK' => 53,
        'CHECK_TODO' => true,
        'ACCOMPLISHED_TODO' => true,
        'NOT_ACCOMPLISHED_TODO' => true,
        'ADD_TODO_AFTER_CHECK_TODO' => true,
        'NOT_ADD_TODO_AFTER_CHECK_TODO' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, string, datetime>
     */
    protected $fillable = [
        'user_uuid',
        'todo_uuid',
        'created_at'
    ];
}
