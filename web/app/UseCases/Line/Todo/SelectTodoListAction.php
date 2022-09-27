<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use DateTime;
use function Psy\debug;

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
     * @return
     */
    public function invoke(object $event, User $line_user, string $action_value)
    {

        if ($action_value === 'ALL_TODO_LIST') {
            $todo_list = $line_user->todo;
        } else if ($action_value === 'WEEKLY_TODO_LIST') {
            $today_date_time = new DateTime();
            $next_week_date_time = $today_date_time->modify('+1 week');
            $next_week = $next_week_date_time->format('Y-m-d');
            $todo_list = Todo::where('user_uuid', $line_user->uuid)
                ->where('date', '<', $next_week)->get();
        } else {
            $todo_list = [];
        }

        $todo_carousel_columns = [];
        foreach ($todo_list as $todo) {
            $todo_carousel_columns[] = Todo::createTodoCarouselColumn($todo);
        }
        $message = Todo::createTodoListTitleMessage($line_user, $action_value, $todo_carousel_columns);
        $todo_carousels = new CarouselTemplateBuilder($todo_carousel_columns);
        $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
        $builder->add(
            new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message['text'])
        );
        $builder->add(new TemplateMessageBuilder('やること一覧', $todo_carousels));
        $this->bot->replyMessage(
            $event->getReplyToken(),
            $builder
        );
        return;
    }
}
