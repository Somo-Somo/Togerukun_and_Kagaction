<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccomplishTodo extends Model
{
    use HasFactory;

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
