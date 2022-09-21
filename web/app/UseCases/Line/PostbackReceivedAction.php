<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use Illuminate\Support\Facades\Log;


class PostbackReceivedAction
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
     * @param App\UseCases\Line\DateResponseAction
     */
    protected $date_response_action;

    /**
     * @param App\UseCases\Line\Todo\SelectTodoListAction
     */
    protected $select_todo_list_action;

    /**
     * @param App\UseCases\Line\DateResponseAction $date_response_action
     * @param App\UseCases\Line\Todo\SelectTodoListAction $select_todo_list_action
     */
    public function __construct(
        DateResponseAction $date_response_action,
        \App\UseCases\Line\Todo\SelectTodoListAction $select_todo_list_action,
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->date_response_action = $date_response_action;
        $this->select_todo_list_action = $select_todo_list_action;
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param object $event
     * @return
     */
    public function invoke(object $event)
    {
        // 該当のユーザーを探す
        $line_user = User::where('line_user_id', $event->getUserId())->first();

        //postbackのデータをactionとuuidで分割
        list($action_data, $uuid_data) = explode("&", $event->getPostbackData());
        [$action_key, $action_value] = explode("=", $action_data);
        [$uuid_key, $uuid_value] = explode("=", $uuid_data);

        if ($action_value === LineUsersQuestion::TODO_LIST) {
            $this->select_todo_list_action->invoke($event, $line_user);
        } elseif ($action_value === LineUsersQuestion::ADD_TODO) {
            if ($uuid_value) {
                $todo = Todo::where('uuid', $uuid_value)->first();
                // 返信メッセージ
                $this->bot->replyText($event->getReplyToken(), Todo::askTodoName($todo));
                //質問の更新
                $line_user->question->update([
                    'question_number' => LineUsersQuestion::TODO,
                    'parent_uuid' => $uuid_value,
                ]);
            }
        } else if ($action_value === LineUsersQuestion::LIMIT_DATE) {
            // 日付に関する質問の場合
            $this->date_response_action->invoke($event, $line_user);
        }
        return;
    }
}
