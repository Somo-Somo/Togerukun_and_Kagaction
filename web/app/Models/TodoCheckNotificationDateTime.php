<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class TodoCheckNotificationDateTime extends Model
{
    use HasFactory;

    const SETTING_NOTIFICATION_FOR_TODO_CHECK = [
        'SETTING_NOTIFICATION_CHECK_TODO' => true,
        'SETTING_NOTIFY_DAY_OF_WEEK' => true,
        'SETTING_NOTIFY_MERIDIEM' => true,
        'SETTING_NOTIFY_DATETIME' => true,
        'CONFIRM_SETTING_NOTIFICATION_CHECK_TODO' => true
    ];

    const NOTIFY_TODO_CHECK = [
        'NOTIFY_TODO_CHECK' => true,
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    /**
     *
     *
     * 通知設定
     *
     *
     */

    /**
     * 曜日設定するときのメッセージ
     *
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function createSettingDayOfWeekMessageBuilder()
    {
        // body
        $body_box_contents = [];
        $day_of_weeks = ['日', '月', '火', '水', '木', '金', '土', '毎日'];
        for ($column = 0; $column < 4; $column++) {
            $body_box_contents[] = new SeparatorComponentBuilder();
            $rows = [];
            for ($row = 0; $row < 2; $row++) {
                $day_of_week = $day_of_weeks[($column * 2) + ($row * 1)] !== '毎日' ?
                    $day_of_weeks[($column * 2) + ($row * 1)] . '曜日' :
                    $day_of_weeks[($column * 2) + ($row * 1)];
                $data =  'action=SETTING_NOTIFY_MERIDIEM&value=' . ($column * 2) + ($row * 1);
                $button_component =  new ButtonComponentBuilder(
                    new PostbackTemplateActionBuilder($day_of_week, $data),
                );
                $button_component->setColor('#5f9ea0');
                $rows[] = $button_component;
                $row === 0 ? $rows[] = new SeparatorComponentBuilder() : false;
            }
            $body_box_contents[] = new BoxComponentBuilder('horizontal', $rows);
        }
        $body_box = new BoxComponentBuilder('vertical', $body_box_contents);
        $flex_message = TodoCheckNotificationDateTime::selectDateTimeFlexMessageBuilder('振り返る曜日を選択してください', $body_box);

        return $flex_message;
    }

    /**
     *
     * 時間設定するときのメッセージ
     *
     * @param string $day_of_week
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function createSettingTimeMessageBuilder(string $day_of_week)
    {
        // body
        $body_box_contents = [];
        for ($column = 0; $column < 6; $column++) {
            $body_box_contents[] = new SeparatorComponentBuilder();
            $rows = [];
            for ($row = 0; $row < 2; $row++) {
                $time = $column + (6 * $row) . ':00';
                $data =  'action=CONFIRM_SETTING_NOTIFICATION_CHECK_TODO&value=' . $day_of_week . '-' . $time;
                $button_component =  new ButtonComponentBuilder(
                    new PostbackTemplateActionBuilder($time, $data),
                );
                $button_component->setColor('#87cefa');
                $rows[] = $button_component;
                $row === 0 ? $rows[] = new SeparatorComponentBuilder() : false;
            }
            $body_box_contents[] = new BoxComponentBuilder('horizontal', $rows);
        }
        $body_box = new BoxComponentBuilder('vertical', $body_box_contents);

        $flex_message = TodoCheckNotificationDateTime::selectDateTimeFlexMessageBuilder('振り返る時間を選択してください', $body_box);
        return $flex_message;
    }

    /**
     *
     * 日時設定のflex_message変換
     *
     * @param string $header_text
     * @param \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder $body_box
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function selectDateTimeFlexMessageBuilder(string $header_text, $body_box)
    {
        // header
        $header_text_builder = new TextComponentBuilder($header_text);
        $header_text_builder->setWeight('bold');
        $header_text_builder->setAlign('center');
        $header_text_builder->setOffsetTop('12px');
        $header_box = new BoxComponentBuilder('vertical', [$header_text_builder]);

        //bubble
        $time_bubble_container = new BubbleContainerBuilder();
        $time_bubble_container->setHeader($header_box);
        $time_bubble_container->setBody($body_box);
        $time_bubble_container->setSize('giga');

        // flex message
        $flex_message = new FlexMessageBuilder(
            '通知日時の設定',
            new CarouselContainerBuilder([$time_bubble_container])
        );
        return $flex_message;
    }


    /**
     *
     * 日時設定のflex_message変換
     *
     * @param string $day_of_week
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function selectMeridiemCarouselMessageBuilder(string $day_of_week)
    {
        $actions = [];
        $meridiems = ['午前', '午後'];

        foreach ($meridiems as $meridiem_key => $meridiem) {
            $meridiem_text_component  = new TextComponentBuilder($meridiem, 1);
            $meridiem_text_component->setWeight('bold');
            $meridiem_text_component->setGravity('center');
            $meridiem_text_component->setAlign('center');
            $text_component = [$meridiem_text_component];
            $post_back_template_action = new PostbackTemplateActionBuilder(
                $meridiem,
                'action=SETTING_NOTIFY_DAY_OF_WEEK&value=' . $day_of_week . '-' . $meridiem_key
            );
            $meridiem_box_component = new BoxComponentBuilder('vertical', $text_component);
            $meridiem_box_component->setAction($post_back_template_action);
            $meridiem_box_component->setHeight('80px');
            $meridiem_bubble_container = new BubbleContainerBuilder();
            $meridiem_bubble_container->setBody($meridiem_box_component);
            $meridiem_bubble_container->setSize('nano');
            $actions[] = $meridiem_bubble_container;
        }
        $meridiem_carousels = new CarouselContainerBuilder($actions);
        $flex_message = new FlexMessageBuilder(
            '時間帯の設定',
            $meridiem_carousels
        );

        $please_select_meridiem_text = new TextMessageBuilder('振り返る時間帯を選択してください!');

        $multi_message_builder = new MultiMessageBuilder();
        $multi_message_builder->add($please_select_meridiem_text);
        $multi_message_builder->add($flex_message);

        return $multi_message_builder;
    }
}
