<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\LineUsersQuestion;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Todo\TodoRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;
use App\Repositories\Line\LineBotRepositoryInterface;
use Illuminate\Support\Str;
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
     * @param array $message
     * @return
     */
    public function invoke(array $message)
    {
        if ($message['message']['type'] === 'text') {
            $question_number = LineUsersQuestion::where('line_user_id', $message['source']['userId'])->first();

            // 質問がない場合
            if ($question_number === LineUsersQuestion::NO_QUESTION) {
                # code...
            }
            // プロジェクトに関する質問の場合
            else if ($question_number === LineUsersQuestion::PROJECT) {
                $project = [
                    'name' => $message['message']['text'],
                    'uuid' => (string) Str::uuid(),
                    'created_by_user_uuid' => User::where('line_user_id', $message['source']['userId'])->value('uuid')
                ];
                // プロジェクトを作成
                $this->project_repository->create($project);
                # code...
            }
            // Todoに関する質問の場合
            else if ($question_number === LineUsersQuestion::TODO) {
                # code...
            }
            // 日付に関する質問の場合
            else if ($question_number === LineUsersQuestion::DATE) {
                # code...
            }
        }
    }
}
