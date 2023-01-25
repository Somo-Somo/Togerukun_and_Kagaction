<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\Project;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Support\Str;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;


class ProjectResponseAction
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
     * @param App\Repositories\Project\ProjectRepositoryInterface
     */
    protected $project_repository;

    /**
     * @param App\Repositories\Project\ProjectRepositoryInterface $project_repository_interface
     */
    public function __construct(
        ProjectRepositoryInterface $project_repository_interface,
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->project_repository = $project_repository_interface;
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param object $event
     * @param User $line_user
     * @return
     */
    public function invoke(object $event, User $line_user)
    {
        $project = [
            'name' => $event->getText(),
            'uuid' => (string) Str::uuid(),
            'created_by_user_uuid' => $line_user->uuid
        ];

        // 返信メッセージ
        $this->bot->replyText(
            $event->getReplyToken(),
            Project::confirmProject($project['name']),
            Todo::askGoal($line_user->name, $project['name'])
        );

        // プロジェクトのSQLへの保存
        Project::create([
            'user_uuid' => $line_user->uuid,
            'name' => $project['name'],
            'uuid' => $project['uuid']
        ]);

        //質問の更新
        $line_user->question->update([
            'question_number' => LineUsersQuestion::GOAL,
            'parent_uuid' => $project['uuid'],
            'project_uuid' => $project['uuid']
        ]);

        // GraphDBに保存
        $this->project_repository->create($project);

        return;
    }
}
