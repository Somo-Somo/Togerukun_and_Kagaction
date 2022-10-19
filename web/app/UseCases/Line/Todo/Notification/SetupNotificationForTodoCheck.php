<?php

namespace App\UseCases\Line\Todo\Notification;

use App\Models\TodoCheckNotificationDateTime;
use App\Models\User;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use Illuminate\Support\Facades\Log;
use DateTime;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class SetupNotificationForTodoCheck
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
        if ($action_type === 'IF_YOU_WANT_TO_SET_UP_NOTIFY_CHECK_TODO') {
            $notification_date_time = TodoCheckNotificationDateTime::where('user_uuid', $line_user->uuid)->get();
            $template_message_builder = TodoCheckNotificationDateTime::createConfirmDoYouWantToSetUpBuilder($notification_date_time);
            $this->bot->replyMessage(
                $event->getReplyToken(),
                $template_message_builder
            );
        } else if ($action_type === 'STOP_SETTING_NOTIFICATION_CHECK_TODO') {
            if (!$notify_setting_value) {
                $this->bot->replyMessage(
                    $event->getReplyToken(),
                    TodoCheckNotificationDateTime::createConfirmYouStopNotificationBuilder()
                );
            } else if ($notify_setting_value === true) {
                // 通知のSTOP
                $this->bot->replyMessage(
                    $event->getReplyToken(),
                    new TextMessageBuilder('振り返りの時間の通知を停止しました。')
                );
            } else if ($notify_setting_value === false) {
                // キャンセル
                $this->bot->replyMessage(
                    $event->getReplyToken(),
                    new TextMessageBuilder('振り返りの時間の通知を停止キャンセルしました。')
                );
            }
        } else if ($action_type === 'SETTING_NOTIFICATION_CHECK_TODO') {
            $setting_day_of_week_message_builder = TodoCheckNotificationDateTime::createSettingDayOfWeekMessageBuilder();
            $this->bot->replyMessage(
                $event->getReplyToken(),
                $setting_day_of_week_message_builder
            );
        } else if ($action_type === 'SETTING_NOTIFY_DAY_OF_WEEK') {
            $setting_time_message_builder = TodoCheckNotificationDateTime::createSettingTimeMessageBuilder($notify_setting_value);
            $test = $this->bot->replyMessage(
                $event->getReplyToken(),
                $setting_time_message_builder
            );
            Log::debug((array)$test);
        } else if ($action_type === 'SETTING_NOTIFY_MERIDIEM') {
            $setting_meridiem_builder = TodoCheckNotificationDateTime::selectMeridiemCarouselMessageBuilder($notify_setting_value);
            $test = $this->bot->replyMessage(
                $event->getReplyToken(),
                $setting_meridiem_builder
            );
            Log::debug((array)$test);
        } else if ($action_type === 'CONFIRM_SETTING_NOTIFICATION_CHECK_TODO') {
            Log::debug($notify_setting_value);
        }
        return;
    }
}
