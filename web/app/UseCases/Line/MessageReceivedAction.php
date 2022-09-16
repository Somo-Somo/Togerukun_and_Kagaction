<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\Project;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Todo\TodoRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;
use App\Repositories\Line\LineBotRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
     * @param App\Repositories\Line\LineBotRepositoryInterface
     */
    protected $line_bot_repository;

    /**
     * @param App\Repositories\Project\ProjectRepositoryInterface
     */
    protected $project_repository;

    /**
     * @param App\Repositories\Line\LineRepositoryInterface $line_repository_interface
     * @param App\Repositories\Project\ProjectRepositoryInterface $project_repository_interface
     * @param App\Repositories\Todo\TodoRepositoryInterface $todo_repository_interface
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     */
    public function __construct(
        LineBotRepositoryInterface $line_bot_repository_interface,
        ProjectRepositoryInterface $project_repository_interface
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->line_bot_repository = $line_bot_repository_interface;
        $this->project_repository = $project_repository_interface;
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param object $event
     * @return
     */
    public function invoke(object $event)
    {
        if ($event->getEvent()['message']['type'] === 'text') {
            // 該当のユーザーを探す
            $line_user = User::where('line_user_id', $event->getUserId())->first();

            $question_number = $line_user->question->question_number;

            // 質問がない場合
            if ($question_number === LineUsersQuestion::NO_QUESTION) {
                # code...
            }
            // プロジェクトに関する質問の場合
            if ($question_number === LineUsersQuestion::PROJECT) {
                $project = [
                    'name' => $event->getText(),
                    'uuid' => (string) Str::uuid(),
                    'created_by_user_id' => $line_user->id
                ];

                // 返信メッセージ
                $this->bot->replyText(
                    $event->getReplyToken(),
                    Project::confirmProject($project['name']),
                    Todo::askGoal($line_user->name, $project['name'])
                );

                //質問の更新
                $line_user->question->update([
                    'question_number' => LineUsersQuestion::TODO
                ]);

                // GraphDBに保存
                $this->project_repository->create($project);

                return;
            }
            // Todoに関する質問の場合
            if ($question_number === LineUsersQuestion::TODO) {
                $todo = [
                    'name' => $event->getText(),
                    'uuid' => (string) Str::uuid(),
                    'parent_uuid' => $line_user->question->parent_uuid,
                    'created_by_user_id' => $line_user->id
                ];
            }
            // 日付に関する質問の場合
            if ($question_number === LineUsersQuestion::DATE) {
                # code...
            }
            return;
        }
    }
}
