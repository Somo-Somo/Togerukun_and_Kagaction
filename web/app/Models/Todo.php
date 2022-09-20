<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

use function Psy\debug;

class Todo extends Model
{
    use HasFactory;

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
     * Todoの期限を聞く
     *
     * @param string $user_name
     * @param string $todo_name
     * @return \LINE\LINEBot\MessageBuilder\MultiMessageBuilder()
     */
    public static function askTodoLimited(string $user_name, string $todo_name)
    {
        $title = '「' . $todo_name . '」の期日';
        $text = 'それでは' . $user_name . 'さんはいつまでに「' . $todo_name . '」を達成したいですか?';
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
                        new DatetimePickerTemplateActionBuilder('期日を選択', 'storeId=12345', 'date')
                    ]
                )
            )
        );
        return $builder;
    }

    /**
     * 日付を確認する
     *
     * @param DateTime $date
     * @return string $reply_message
     */
    public static function confirmDate(DateTime $date)
    {
        return  '「' . $date->format('Y年m月d日') . '」だね！';
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
}
