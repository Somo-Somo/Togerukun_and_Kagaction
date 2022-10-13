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
        string $notify_setting_value
    ) {
        if ($action_type === 'SETTING_NOTIFICATION_CHECK_TODO') {
            $setting_day_of_week_message_builder = CheckedTodo::createSettingDayOfWeekMessageBuilder();
            $this->bot->replyMessage(
                $event->getReplyToken(),
                $setting_day_of_week_message_builder
            );
        } else if ($action_type === 'SETTING_NOTIFY_DAY_OF_WEEK') {
            $setting_time_message_builder = CheckedTodo::createSettingTimeMessageBuilder($notify_setting_value);
            $test = $this->bot->replyMessage(
                $event->getReplyToken(),
                $setting_time_message_builder
            );
            Log::debug((array)$test);
        }
        return;
    }
}
