<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\LineUsersQuestion;
use App\UseCases\Line\ProjectResponseAction;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;


class MessageReceivedAction
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
     * @param App\UseCases\Line\ProjectResponseAction
     */
    protected $project_response_action;

    /**
     * @param App\UseCases\Line\TodoResponseAction
     */
    protected $todo_response_action;

    /**
     * @param App\UseCases\LINE\ProjectResponseAction $project_response_action
     * @param App\UseCases\LINE\TodoResponseAction $todo_response_action
     */
    public function __construct(
        ProjectResponseAction $project_response_action,
        TodoResponseAction $todo_response_action,
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->project_response_action = $project_response_action;
        $this->todo_response_action = $todo_response_action;
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

        if ($question_number === LineUsersQuestion::NO_QUESTION) {
            // 質問がない場合
            if ($event->getText() === '振り返る') {
                # code...
            } else if ($event->getText() === 'やること') {
            } else if ($event->getText() === 'やること') {
                # code...
            }
        } else if ($question_number === LineUsersQuestion::PROJECT) {
            // プロジェクトに関する質問の場合
            $this->project_response_action->invoke($event, $line_user);
        } else if (
            $question_number === LineUsersQuestion::GOAL ||
            $question_number === LineUsersQuestion::TODO
        ) {
            // TodoやGOALに関する質問の場合
            $this->todo_response_action->invoke($event, $line_user, $question_number);
        }
        return;
    }
}
