<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use DateTime;

class SelectTodoListAction
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
     *
     */
    public function __construct()
    {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param object $event
     * @param User $line_user
     * @param string $action_value
     * @param string $current_page
     * @return
     */
    public function invoke(object $event, User $line_user, string $action_value, string $current_page)
    {
        $current_page = intval($current_page);

        $today_date_time = new DateTime();
        $today = $today_date_time->format('Y-m-d');
        if ($action_value === 'ALL_TODO_LIST') {
            $todo_list = $line_user->todo;
        } elseif ($action_value === 'WEEKLY_TODO_LIST') {
            $next_week_date_time = $today_date_time->modify('+1 week');
            $next_week = $next_week_date_time->format('Y-m-d');
            $todo_list = Todo::where('user_uuid', $line_user->uuid)
                ->whereBetween('date', [$today, $next_week])
                ->orderBy('date', 'asc')
                ->get();
        } else {
            $todo_list = [];
        }

        $todo_carousel_columns = [];
        foreach ($todo_list as $todo) {
            if (count($todo->accomplish) === 0) {
                $todo_carousel_columns[] = Todo::createBubbleContainer($todo, $action_value);
            }
        }

        if ($action_value === 'WEEKLY_TODO_LIST') {
            $over_due_todo_list = Todo::where('user_uuid', $line_user->uuid)
                ->where('date', '<', $today)
                ->orderBy('date', 'asc')
                ->get();
            foreach ($over_due_todo_list as $over_due_todo) {
                if (count($over_due_todo->accomplish) === 0) {
                    $todo_carousel_columns[] = Todo::createBubbleContainer($over_due_todo, $action_value);
                }
            }
        }

        $count_todo_carousel_column = count($todo_carousel_columns);

        // Todoが8件以上ある時
        if ($count_todo_carousel_column > 9) {
            $todo_carousel_limit = $current_page === 1 ? 9 : 10;
            $slice_start = $current_page === 1 ? 0 : ($todo_carousel_limit - 1) + (($current_page - 1) * 10);
            $todo_carousel_columns = array_slice($todo_carousel_columns, $slice_start, $todo_carousel_limit);
            $todo_carousel_columns[] = Todo::createViewMoreBubbleContainer($todo_carousel_limit, $current_page, $count_todo_carousel_column);
        }

        // Todoが何件あるか報告するメッセージ
        $report_message = Todo::createCountTodoBubbleContainer($line_user, $action_value, $count_todo_carousel_column);
        array_unshift($todo_carousel_columns, $report_message);

        $todo_carousels = new CarouselContainerBuilder($todo_carousel_columns);
        $flex_message = new FlexMessageBuilder(
            'やること一覧',
            $todo_carousels
        );
        $log = $this->bot->replyMessage(
            $event->getReplyToken(),
            $flex_message
        );
        Log::debug((array)$log);
        return;
    }
}
