<?php

namespace App\UseCases\Line\Todo;

use App\Models\CheckedTodo;
use App\Models\User;
use App\Repositories\Todo\TodoRepositoryInterface;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use Illuminate\Support\Facades\Log;

class NotifyCheckTodo
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
        string $notification_date_value
    ) {
        Log::debug((array)$notification_date_value);
        if (!$notification_date_value) {
            Log::debug('aaa');
            $setting_day_of_week_message_builder = CheckedTodo::createSettingDayOfWeekMessageBuilder();
            $test = $this->bot->replyMessage(
                $event->getReplyToken(),
                $setting_day_of_week_message_builder
            );
            Log::debug((array)$test);
        }
        return;
    }
}
