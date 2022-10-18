<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoCheckNotificationDateTime extends Model
{
    use HasFactory;

    const SETTING_NOTIFICATION_FOR_TODO_CHECK = [
        'SETTING_NOTIFICATION_CHECK_TODO' => true,
        'SETTING_NOTIFY_DAY_OF_WEEK' => true,
        'SETTING_NOTIFY_DATETIME' => true,
    ];

    const NOTIFY_TODO_CHECK = [
        'NOTIFY_TODO_CHECK' => true,
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'uuid', 'user_uuid');
    }
}
