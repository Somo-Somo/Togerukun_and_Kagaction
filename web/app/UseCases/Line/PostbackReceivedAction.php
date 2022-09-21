<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\LineUsersQuestion;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;


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
        Todo\SelectTodoListAction $select_todo_list_action,
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

        $question_number = $line_user->question->question_number;

        if ($event->getPostbackData() === LineUsersQuestion::TODO_LIST) {
            $this->select_todo_list_action->invoke($event, $line_user);
        } elseif ($event->getPostbackData() === LineUsersQuestion::ADD_TODO) {
            # code...
        } else if ($event->getPostbackData() === LineUsersQuestion::LIMIT_DATE) {
            // 日付に関する質問の場合
            $this->date_response_action->invoke($event, $line_user);
        }
        return;
    }
}
