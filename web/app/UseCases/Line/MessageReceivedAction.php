<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\Project;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Goal\GoalRepositoryInterface;
use App\Repositories\Todo\TodoRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;
use App\Repositories\Line\LineBotRepositoryInterface;
use App\UseCases\Line\ProjectResponseAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use DateTime;


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
     * @param App\Repositories\Goal\GoalRepositoryInterface
     */
    protected $goal_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface
     */
    protected $todo_repository;

    /**
     * @param App\Repositories\Todo\DateRepositoryInterface
     */
    protected $date_repository;

    /**
     * @param App\UseCases\Line\ProjectResponseAction
     */
    protected $project_response_action;

    /**
     * @param App\Repositories\Line\LineRepositoryInterface $line_repository_interface
     * @param App\Repositories\Project\ProjectRepositoryInterface $project_repository_interface
     * @param App\Repositories\Goal\GoalRepositoryInterface $todo_repository_interface
     * @param App\Repositories\Todo\TodoRepositoryInterface $todo_repository_interface
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     * @param App\UseCases\LINE\ProjectResponseAction $project_response_action
     */
    public function __construct(
        LineBotRepositoryInterface $line_bot_repository_interface,
        ProjectRepositoryInterface $project_repository_interface,
        GoalRepositoryInterface $goal_repository_interface,
        TodoRepositoryInterface $todo_repository_interface,
        DateRepositoryInterface $date_repository_interface,
        ProjectResponseAction $project_response_action,
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->line_bot_repository = $line_bot_repository_interface;
        $this->project_repository = $project_repository_interface;
        $this->goal_repository = $goal_repository_interface;
        $this->todo_repository = $todo_repository_interface;
        $this->date_repository = $date_repository_interface;
        $this->project_response_action = $project_response_action;
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
        } else if ($question_number === LineUsersQuestion::PROJECT) {
            // プロジェクトに関する質問の場合
            $this->project_response_action->invoke($event, $line_user);
        } else if (
            $question_number === LineUsersQuestion::GOAL ||
            $question_number === LineUsersQuestion::TODO
        ) {
            // TodoやGOALに関する質問の場合
            $todo = [
                'name' => $event->getText(),
                'uuid' => (string) Str::uuid(),
                'parent_uuid' => $line_user->question->parent_uuid,
                'user_uuid' => $line_user->uuid
            ];

            // 返信メッセージ(日付)
            $this->bot->replyMessage(
                $event->getReplyToken(),
                Todo::askTodoLimited($line_user->name, $todo['name'])
            );

            //質問の更新
            $line_user->question->update([
                'question_number' => LineUsersQuestion::DATE,
                'parent_uuid' => $todo['uuid']
            ]);

            // GraphDBに保存
            $question_number === LineUsersQuestion::GOAL ?
                $this->goal_repository->create($todo) : $this->todo_repository->create($todo);
        } else if ($question_number === LineUsersQuestion::DATE) {
            // 日付に関する質問の場合
            $date = [
                'uuid' => $line_user->question->parent_uuid,
                'user_uuid' => $line_user->uuid,
                'date' => $event->getPostbackParams()['date']
            ];

            $this->bot->replyText(
                $event->getReplyToken(),
                Todo::confirmDate(new DateTime($date['date'])),
                Todo::callForAdditionalTodo(),
                Todo::explainSettingOfCheck()
            );

            //質問の更新
            $line_user->question->update([
                'question_number' => LineUsersQuestion::NO_QUESTION,
                'parent_uuid' => null
            ]);

            $this->date_repository->updateDate($date);
        }
        return;
    }
}
