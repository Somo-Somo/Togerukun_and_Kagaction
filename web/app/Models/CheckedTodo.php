<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

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
        'FINISH_CHECK_TODO' => true,
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

    /**
     * どのTodoたちを振り返るか尋ねる
     *
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    public static function askWhichCheckTodo()
    {
        $builder =
            new TemplateMessageBuilder(
                '振り返り', // チャット一覧に表示される
                new ButtonTemplateBuilder(
                    'どちらのやることを振り返りますか？', // title
                    '選択してください', // text
                    null, // 画像url
                    [
                        new PostbackTemplateActionBuilder('今日までにやること', 'action=CHECK_TODO_BY_TODAY&project_uuid='),
                        new PostbackTemplateActionBuilder('今週までにやること', 'action=CHECK_TODO_BY_THIS_WEEK&todo_uuid='),
                        new PostbackTemplateActionBuilder('やること一覧から選択', 'action=SELECT_TODO_LIST_TO_CHECK&todo_uuid='),
                    ]
                )
            );
        return $builder;
    }
}
