<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'user_uuid',
        'todo_uuid',
        'interval',
        'day',
        'consecutive_days',
        'created_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'interval' => 'integer',
        'day' => 'integer',
        'consecutive_days' => 'integer',
    ];

    /**
     * ユーザーに紐づく習慣
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    /**
     * todoに紐づく習慣
     *
     * @return
     */
    public function todo()
    {
        return $this->belongsTo(Todo::class, 'todo_uuid', 'uuid');
    }
}
