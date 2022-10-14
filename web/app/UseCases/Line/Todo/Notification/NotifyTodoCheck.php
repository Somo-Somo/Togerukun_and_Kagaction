<?php

namespace App\UseCases\Line\Todo\Notification;

use App\Models\CheckedTodo;
use App\Models\User;
use App\Models\Todo;
use App\Repositories\Todo\TodoRepositoryInterface;
use App\UseCases\Line\Todo\CreateTodoListCarouselColumns as TodoCreateTodoListCarouselColumns;
use pp\UseCases\Line\Todo\CreateTodoListCarouselColumns;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
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
     * Todoの名前を変更する
     *
     * @param object $event
     * @param User $line_user
     * @return
     */
    public function invoke(
        object $event,
        User $line_user,
        string $action_type,
        string $notify_setting_value
    ) {
        $recive_notification_users = [];
        if (count($recive_notification_users) > 0) {
            foreach ($recive_notification_users as  $recive_notification_user) {
                $notify_todo_check_message =
                    '振り返りの時間です。' . "\n" . $line_user->name . 'さんが今日までにやるもの一覧です。' . "\n" . '頑張って振り返っていきましょう!';
                $today_date_time = new DateTime();
                $today = $today_date_time->format('Y-m-d');
                $todo_list = Todo::where('user_uuid', $line_user->uuid)
                    ->where('date', '<=', $today)
                    ->orderBy('date', 'desc')
                    ->get();
                $create_todo_list_carousel_columns_action = new TodoCreateTodoListCarouselColumns();
                $todo_list_carousel_flex_message = $create_todo_list_carousel_columns_action->invoke(
                    $line_user,
                    $todo_list,
                    $action_type,
                    $current_page = 1
                );
            }
        }


        return;
    }
}
