<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use SebastianBergmann\Template\Template;

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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'depth' => 'integer',
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
     * 達成したTodo
     *
     */
    public function accomplish()
    {
        return $this->hasMany(AccomplishTodo::class, 'todo_uuid', 'uuid');
    }

    /**
     * 振り返りを行なったTodo
     *
     */
    public function checked()
    {
        return $this->hasMany(CheckedTodo::class, 'todo_uuid', 'uuid');
    }


    /**
     *
     * ゴール Goal
     *
     */

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
     *
     *
     * やること Todo
     *
     *
     */

    /**
     * Todoの名前を聞く
     *
     * @param Todo $todo
     * @return string $reply_message
     */
    public static function askTodoName(Todo $todo)
    {
        return '「' . $todo->name . '」を達成するためにやることを教えてください！';
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
     * Todoのカルーセル
     *
     * @param object $todo
     * @return LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
     */
    public static function createTodoCarouselColumn(object $todo)
    {
        if ($todo->depth === "0") {
            $parent = 'プロジェクト:「' . $todo->project->name . '」のゴール';
        } else {
            $parentTodo = Todo::where('uuid', $todo->parent_uuid)->first();
            $parent = '「' . $parentTodo->name . '」のためにやること';
        }
        $accomplish = count($todo->accomplish) > 0 ? '【達成】' : '【未達成】';
        $title = $accomplish . $todo->name;
        $actions = [
            new PostbackTemplateActionBuilder('名前・期限の変更/削除', 'action=CHANGE_TODO&todo_uuid=' . $todo->uuid),
            new PostbackTemplateActionBuilder('やることの追加', 'action=ADD_TODO&todo_uuid=' . $todo->uuid),
            new PostbackTemplateActionBuilder('振り返る', 'action=SELECT_CHECK_TODO&todo_uuid=' . $todo->uuid),
        ];
        $builder = new CarouselColumnTemplateBuilder($title, $parent, null, $actions);
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
     * Todoを変更する
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

    /**
     *
     *
     * やること一覧 TodoList
     *
     *
     */

    /**
     * Todoの一覧を見るか、それとも新しくTodoを追加するか尋ねる
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
     * Todoの一覧表示
     *
     * @param User $line_user
     * @param string $action_value
     * @param array $todo_carousel_columns
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    public static function createTodoListTitleMessage(User $line_user, string $action_value, array $todo_carousel_columns)
    {
        if (
            $action_value === 'ALL_TODO_LIST' ||
            $action_value === 'SELECT_TODO_LIST_TO_CHECK'
        ) {
            $title = '「' . $line_user->question->project->name . '」のやること一覧';
            $text =   'プロジェクト:「' . $line_user->question->project->name . '」のやることは' . count($line_user->todo) . '件です';
        } else if (
            $action_value === 'WEEKLY_TODO_LIST' ||
            $action_value === 'CHECK_TODO_BY_THIS_WEEK'
        ) {
            $title = $line_user->name . 'さんの今週までにやること一覧';
            $text = $line_user->name . 'さんの今週までにやることは' . count($todo_carousel_columns) . '件です';
        } else if ($action_value === 'CHECK_TODO_BY_TODAY') {
            $title = $line_user->name . 'さんの今日までにやること一覧';
            $text = $line_user->name . 'さんの今日までにやることは' . count($todo_carousel_columns) . '件です';
        }
        return ['title' => $title, 'text' => $text];
    }

    /**
     * TodoListに戻る
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
     *
     *
     * やることの期限 Date
     *
     *
     */

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
     * 変更後の日付を報告する
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
     * 日付の削除を確認する
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
     *
     *
     * Flexメッセージのカラム
     *
     *
     */

    /**
     *
     * コンポーネントをひとまとめ。BubbleContainerの生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createBubbleContainer(Todo $todo)
    {
    }

    /**
     *
     * Header
     *
     **/

    /**
     *
     * ヘッダーに必要なコンポーネント総集め。Headerコンポーネントの生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createHeaderComponent(Todo $todo)
    {
    }

    /**
     *
     * サブタイトル
     *
     **/

    /**
     * Todoのサブタイトル（親Todo）をひとまとめ。
     * Boxのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder
     */
    public static function createSubtitleBoxComponent(Todo $todo)
    {
        $subtitle_text_component = Todo::createSubtitleTextComponent($todo);
        $subtitle_icon_component = Todo::createSubtitleIconComponent($todo);
        return new BoxComponentBuilder(
            'baseline',
            [$subtitle_text_component, $subtitle_icon_component]
        );
    }

    /**
     * Todoのサブタイトル（親Todo）のテキストコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    private function createSubtitleTextComponent(Todo $todo)
    {
        if ($todo->depth === "0") {
            $subtitle_text = 'プロジェクト:「' . $todo->project->name . '」のゴール';
        } else {
            $parentTodo = Todo::where('uuid', $todo->parent_uuid)->first();
            $subtitle_text = '「' . $parentTodo->name . '」のためにやること';
        }
        $subtitle_text_component = new TextComponentBuilder($subtitle_text);
        $subtitle_text_component->setSize("xss");
        $subtitle_text_component->setColor("#aaaaaa");
        return $subtitle_text_component;
    }

    /**
     * Todoのサブタイトル（親Todo）のアイコンのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder
     */
    public static function createSubtitleIconComponent(Todo $todo)
    {
        $url = $todo->depth === 0 ? '/web/public/svg/goal-flag.svg' : '/web/public/svg/todo-tree.svg';
        return new IconComponentBuilder(
            $url, // 画像URL
            "xs", // margin
            "xxs", // size
            null // aspectoRatio
        );
    }

    /**
     *
     * 日付
     *
     **/

    /**
     * 日付をひとまとめ
     * Boxコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createDateBoxComponent(Todo $todo)
    {
    }

    /**
     * 日付タイトルの日付のコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createDateTextComponent(Todo $todo)
    {
    }

    /**
     * 日付メッセージのアイコンのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createDateIconComponent(Todo $todo)
    {
    }

    /**
     * Todoのタイトルのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createFilterComponent(Todo $todo)
    {
    }

    /**
     * Todoの完了のゲージのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createAccomplishGageComponent(Todo $todo)
    {
    }

    /**
     *
     * Body
     *
     **/

    /**
     * Body部分のコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createBodyComponent(Todo $todo)
    {
    }

    /**
     * Postbackなボタンのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createPostBackButtonComponent(Todo $todo)
    {
    }
}
