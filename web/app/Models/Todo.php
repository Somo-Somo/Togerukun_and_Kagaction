<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;

use function Psy\debug;

class Todo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_uuid',
        'name',
        'uuid',
        'parent_uuid',
        'project_uuid',
        'date',
        'accomplish',
        'depth',
        'created_at'
    ];

    /**
     * Todoに紐づくProject
     *
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_uuid', 'uuid');
    }

    /**
     * プロジェクトのゴールを聞く
     *
     * @param string $user_name
     * @param string $project_name
     * @return string $reply_message
     */
    public static function askGoal(string $user_name, string $project_name)
    {
        return '今度は' . $user_name . 'さんが「' . $project_name . '」で達成したいゴールを教えて！';
    }

    /**
     * プロジェクトのゴールを聞く
     *
     * @param Todo $todo
     * @return string $reply_message
     */
    public static function askTodoName(Todo $todo)
    {
        return '「' . $todo->name . '」を達成するためにやることを教えてください！';
    }

    /**
     * Todoの期限を聞く
     *
     * @param string $user_name
     * @param array $todo
     * @return \LINE\LINEBot\MessageBuilder\MultiMessageBuilder()
     */
    public static function askTodoLimited(string $user_name, array $todo)
    {
        $title = '「' . $todo['name'] . '」の期日';
        $text = 'それでは' . $user_name . 'さんはいつまでに「' . $todo['name'] . '」を達成したいですか?';
        $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
        $builder->add(new TextMessageBuilder($text));
        $builder->add(
            new TemplateMessageBuilder(
                $title, // チャット一覧に表示される
                new ButtonTemplateBuilder(
                    $title, // title
                    'いつまでに達成したいか考えてみよう！', // text
                    null, // 画像url
                    [
                        new DatetimePickerTemplateActionBuilder('期日を選択', 'action=LIMIT_DATE&todo_uuid=' . $todo['uuid'], 'date')
                    ]
                )
            )
        );
        return $builder;
    }

    /**
     * Todoの期限を聞く
     *
     * @param string $user_name
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\MultiMessageBuilder()
     */
    public static function askReschedule(Todo $todo)
    {
        $date = new DateTime($todo->date);
        $builder =
            new TemplateMessageBuilder(
                '期日の変更', // チャット一覧に表示される
                new ButtonTemplateBuilder(
                    $todo->name . 'の期日', // title
                    $date->format('Y年m月d日') . 'までに' . $todo->name, // text
                    null, // 画像url
                    [
                        new DatetimePickerTemplateActionBuilder('期日の変更', 'action=RESCHEDULE&todo_uuid=' . $todo->uuid, 'date')
                    ]
                )
            );
        return $builder;
    }

    /**
     * 変更後のTodoの名前を聞く
     *
     * @param Todo $todo
     * @return string $reply_message
     */
    public static function askTodoReName(Todo $todo)
    {
        return 'やること:「' . $todo->name . '」の変更後の名前を教えてください！';
    }

    /**
     * 日付を確認する
     *
     * @param Todo $todo
     * @param DateTime $date
     * @return string $reply_message
     */
    public static function confirmDate(Todo $todo, DateTime $date)
    {
        $confirm =  '「' . $date->format('Y年m月d日') . '」ですね！';
        $fighting =  'それでは' . $date->format('Y年m月d日') . 'までに「' . $todo->name . '」が達成できるよう頑張っていきましょう！';
        return $confirm . "\n" . $fighting;
    }

    /**
     * 日付を確認する
     *
     * @param Todo $todo
     * @param DateTime $date
     * @return string $reply_message
     */
    public static function confirmReschedule(Todo $todo, DateTime $new_date)
    {
        $old_date = new DateTime($todo->date);
        return '「' . $todo->name . '」の期限を' . $old_date->format('Y年m月d日') . 'から' .  $new_date->format('Y年m月d日') . 'に変更しました';
    }

    /**
     * 名前の変更を確認する
     *
     * @param Todo $todo
     * @param DateTime $date
     * @return string $reply_message
     */
    public static function reportNewTodoName(Todo $todo, string $new_todo_name)
    {
        return '「' . $todo->name . '」から「' . $new_todo_name . '」へ変更が完了しました';
    }

    /**
     * 日付を確認する
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    public static function confirmDeleteTodo(Todo $todo)
    {
        $text = 'やること:「' . $todo->name . '」を削除してもよろしいですか？' . "\n" . '「' . $todo->name . '」を達成するために設定したやることも全て削除されてしまいます。';
        $builder =
            new TemplateMessageBuilder(
                '削除の確認',
                new ConfirmTemplateBuilder(
                    $text, // title
                    [
                        new PostbackTemplateActionBuilder('はい', 'action=OK_DELETE_TODO&project_uuid=' . $todo->uuid),
                        new PostbackTemplateActionBuilder('いいえ', 'action=NOT_DELETE_TODO&todo_uuid=' . $todo->uuid),
                    ]
                )
            );
        return $builder;
    }


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

    /**
     * Todoの一覧を見るか、それとも新しくTodoを追加するか
     *
     * @param string $line_user_name
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    public static function askAddOrList(string $line_user_name)
    {
        $builder =
            new TemplateMessageBuilder(
                'やること', // チャット一覧に表示される
                new ButtonTemplateBuilder(
                    $line_user_name . 'さんのやること', // title
                    '選択してください', // text
                    null, // 画像url
                    [
                        new PostbackTemplateActionBuilder('一覧を見る', 'action=ALL_TODO_LIST&project_uuid='),
                        new PostbackTemplateActionBuilder('今週までにやることをみる', 'action=WEEKLY_TODO_LIST&todo_uuid='),
                    ]
                )

            );
        return $builder;
    }

    /**
     * Todoの一覧を見るか、それとも新しくTodoを追加するか
     *
     * @param User $line_user
     * @param string $action_value
     * @param object $todo_list
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    public static function createTodoListTitleMessage(User $line_user, string $action_value, object $todo_list)
    {
        if ($action_value === 'ALL_TODO_LIST') {
            $title = '「' . $line_user->question->project->name . '」のやること一覧';
            $text =   'プロジェクト:「' . $line_user->question->project->name . '」のやることは' . count($line_user->todo) . '件あります';
        } else if ($action_value === 'WEEKLY_TODO_LIST') {
            $title = $line_user->name . 'さんの今週までにやること一覧';
            $text = $line_user->name . 'さんの今週までにやることは' . count($todo_list) . '件あります';
        }

        $builder =
            new TemplateMessageBuilder(
                'やること', // チャット一覧に表示される
                new ButtonTemplateBuilder(
                    $title, // title
                    $text, // text
                    null, // 画像url
                    [
                        new PostbackTemplateActionBuilder(
                            '新しくゴールを追加',
                            'action=CREATE_GOAL&project_uuid=' . $line_user->question->project_uuid
                        )
                    ]
                )
            );
        return $builder;
    }

    /**
     * Todoの一覧を見るか、それとも新しくTodoを追加するか
     *
     * @param object $todo
     * @return LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
     */
    public static function createTodoCarouselColumn(object $todo)
    {
        if ($todo->depth === "0") {
            $parentTitle = 'プロジェクト:「' . $todo->project->name . '」のゴール';
        } else {
            $parentTodo = Todo::where('uuid', $todo->parent_uuid)->first();
            $parentTitle = '「' . $parentTodo->name . '」のためにやること';
        }
        $actions = [
            new PostbackTemplateActionBuilder('名前・期限の変更/削除', 'action=CHANGE_TODO&todo_uuid=' . $todo->uuid),
            new PostbackTemplateActionBuilder('やることの追加', 'action=ADD_TODO&todo_uuid=' . $todo->uuid),
            new PostbackTemplateActionBuilder('振り返る', 'action=CHECK_TODO&todo_uuid=' . $todo->uuid),
        ];
        $builder = new CarouselColumnTemplateBuilder($todo->name, $parentTitle, null, $actions);
        return $builder;
    }

    /**
     * 作ったTodoのTodoを新しく追加する
     *
     * @param object $todo
     * @return LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
     */
    public static function continueAddTodoOfTodo(object $todo)
    {
        $carouselText =  '「' . $todo->name . '」を達成するためにやることを新しく追加しますか?';
        $actions = [
            new PostbackTemplateActionBuilder('追加する', 'action=ADD_TODO&todo_uuid=' . $todo->uuid),
        ];
        $builder = new CarouselColumnTemplateBuilder(null, $carouselText, null, $actions);
        return $builder;
    }

    /**
     * 引き続き親TodoのTodoを追加する
     *
     * @param object $parentTodo
     * @return LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
     */
    public static function continueAddTodoOfParentTodo(object $parent_todo)
    {
        $carouselText =  '「' . $parent_todo->name . '」を達成するためにやることを引き続き追加しますか?';
        $actions = [
            new PostbackTemplateActionBuilder('追加する', 'action=ADD_TODO&todo_uuid=' . $parent_todo->uuid),
        ];
        $builder = new CarouselColumnTemplateBuilder(null, $carouselText, null, $actions);
        return $builder;
    }

    /**
     * 引き続き親TodoのTodoを追加する
     *
     * @param object $project
     * @return LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
     */
    public static function comeBackTodoList(object $project)
    {
        $carouselText =  '「' . $project->name . '」のやること一覧に戻りますか？';
        $actions = [
            new PostbackTemplateActionBuilder('戻る', 'action=TODO_LIST&todo_uuid=' . $project->uuid),
        ];
        $builder = new CarouselColumnTemplateBuilder(null, $carouselText, null, $actions);
        return $builder;
    }

    /**
     * 引き続き親TodoのTodoを追加する
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    public static function changeTodo(Todo $todo)
    {
        $title =  '「' . $todo->name . '」';
        return new TemplateMessageBuilder(
            $title,
            new ButtonTemplateBuilder(
                $title,
                "選択してください",
                null,
                [
                    new PostbackTemplateActionBuilder("名前の変更", 'action=RENAME_TODO&todo_uuid=' . $todo->uuid),
                    new PostbackTemplateActionBuilder('やることの削除', 'action=DELETE_TODO&todo_uuid=' . $todo->uuid),
                    new PostbackTemplateActionBuilder('期限の変更', 'action=ASK_RESCHEDULE&todo_uuid=' . $todo->uuid),
                ]
            )
        );
    }
}
