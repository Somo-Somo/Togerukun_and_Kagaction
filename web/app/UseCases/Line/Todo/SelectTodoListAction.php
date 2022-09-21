<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

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
    public function invoke(object $event, User $line_user)
    {
        $todo_carousel_columns = [];
        foreach ($line_user->todo as $todo) {
            $todo_carousel_columns[] = Todo::createTodoCarouselColumn($todo);
        }
        $todo_carousels = new CarouselTemplateBuilder($todo_carousel_columns);
        $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
        $builder->add(Todo::createTodoListTitleMessage($line_user));
        $builder->add(new TemplateMessageBuilder('やること一覧', $todo_carousels));
        $this->bot->replyMessage(
            $event->getReplyToken(),
            $builder
        );
        return;
    }
}
