<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['user_uuid'];

    /**
     * Todo追加の呼びかけ
     *
     * @return string $reply_message
     */
    public static function callForAdditionalTodo()
    {
        return  'ゴールを達成するためにやることをどんどん追加していきましょう！' . "\n" . '「メニュー>やること」からゴール達成のためのやることを追加していくことができます！';
    }

    /**
     * 振り返りの設定の説明
     *
     * @return string $reply_message
     */
    public static function explainSettingOfCheck()
    {
        return  'また週に1回、もくサポくんから振り返りのリマインドを送ることができるよ！' . "\n" . '「メニュー>振り返り」から日時の設定を自分で変更することができます！';
    }
}
