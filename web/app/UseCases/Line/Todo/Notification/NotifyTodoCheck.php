<?php

namespace App\UseCases\Line\Todo\Notification;

use App\Models\Todo;
use App\Models\TodoCheckNotificationDateTime;
use App\UseCases\Line\Todo\CreateTodoListCarouselColumns as TodoCreateTodoListCarouselColumns;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Illuminate\Support\Facades\Log;
use DateTime;


class NotifyTodoCheck
{
    /**
     * @param LINE\LINEBot\HTTPClient\CurlHTTPClient
     */
    protected $httpClient;

    /**
     * @param LINE\LINEBot
     */
    protected $bot;


    /**
     */
    public function __construct()
    {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
    }

    /**
     * 該当するユーザーに振り返りの通知を送る
     *
     * @return
     */
    public function invoke()
    {
        $datetime = new DateTime();
        $time = $datetime->format('H') . ':00:00';
        $day_of_week = date('w');
        $at_this_time_user = TodoCheckNotificationDateTime::where('notification_time', $time);
        $at_this_time_on_this_day_of_week_user = $at_this_time_user->where('notification_date', $day_of_week)->get();
        $at_this_time_on_every_day_user = $at_this_time_user->where('notification_date', 7)->get();
        $recive_notification_users = array_merge($at_this_time_on_this_day_of_week_user, $at_this_time_on_every_day_user);
        if (count($recive_notification_users) > 0) {
            foreach ($recive_notification_users as  $recive_notification_user) {
                $notify_todo_check_message =
                    '振り返りの時間です。' . "\n" . $recive_notification_user->users->name . 'さんが今日までにやるもの一覧です。' . "\n" . '頑張って振り返っていきましょう!';
                $today_date_time = new DateTime();
                $today = $today_date_time->format('Y-m-d');
                $todo_list = Todo::where('user_uuid', $recive_notification_user->user_uuid)
                    ->where('date', '<=', $today)
                    ->orderBy('date', 'desc')
                    ->get();
                $create_todo_list_carousel_columns_action = new TodoCreateTodoListCarouselColumns();
                $todo_list_carousel_flex_message = $create_todo_list_carousel_columns_action->invoke(
                    $recive_notification_user->users,
                    $todo_list,
                    $action_type = 'NOTIFY_TODO_CHECK',
                    $current_page = 1
                );
                $multi_message_builder = new MultiMessageBuilder();
                $multi_message_builder->add(new TextMessageBuilder($notify_todo_check_message));
                $multi_message_builder->add($todo_list_carousel_flex_message);
                $this->bot->pushMessage(
                    $recive_notification_user->user_uuid,
                    $multi_message_builder
                );
            }
        }
        return;
    }
}
