<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Models\CheckedTodo;
use App\Models\Habit;
use App\Models\LineUsersQuestion;
use App\Models\Onboarding;
use App\Repositories\Date\DateRepositoryInterface;
use App\Repositories\Line\LineBotRepositoryInterface;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use DateTime;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

class HabitSettingResponseAction
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
     * @param App\Repositories\Line\LineBotRepositoryInterface
     */
    protected $line_bot_repository;

    /**
     * @param App\Repositories\Todo\DateRepositoryInterface
     */
    protected $date_repository;

    /**
     * @param App\Repositories\Line\LineRepositoryInterface $line_repository_interface
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     */
    public function __construct(
        LineBotRepositoryInterface $line_bot_repository_interface,
        DateRepositoryInterface $date_repository_interface,
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->line_bot_repository = $line_bot_repository_interface;
        $this->date_repository = $date_repository_interface;
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param object $event
     * @param User $line_user
     * @param string $action_type
     * @param string $uuid_value
     * @return
     */
    public function invoke(object $event, User $line_user, string $action_type, string $postback_value)
    {
        [$todo_uuid, $frequency] = explode("-", $postback_value);
        if ($frequency === Habit::FREQUENCY['毎週']) {
            $multi_message_builder = Habit::selectDayOfWeek($todo_uuid, $frequency);
            $this->bot->replyMessage($event->getReplyToken(), $multi_message_builder);
        } else if ($frequency === Habit::FREQUENCY['毎月']) {
        }
        Log::debug($todo_uuid);
        Log::debug($frequency);
        return;
    }
}
