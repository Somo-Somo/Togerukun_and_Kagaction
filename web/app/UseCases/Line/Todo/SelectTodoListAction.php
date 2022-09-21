<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use App\Repositories\Goal\GoalRepositoryInterface;
use App\Repositories\Todo\TodoRepositoryInterface;
use App\Repositories\Line\LineBotRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use DateTime;
use Egulias\EmailValidator\Warning\TLD;

class SelectTodoListAction
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
     * @param App\Repositories\Goal\GoalRepositoryInterface
     */
    protected $goal_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface
     */
    protected $todo_repository;

    /**
     * @param App\Repositories\Line\LineRepositoryInterface $line_repository_interface
     * @param App\Repositories\Project\ProjectRepositoryInterface $project_repository_interface
     * @param App\Repositories\Goal\GoalRepositoryInterface $todo_repository_interface
     * @param App\Repositories\Todo\TodoRepositoryInterface $todo_repository_interface
     */
    public function __construct(
        LineBotRepositoryInterface $line_bot_repository_interface,
        GoalRepositoryInterface $goal_repository_interface,
        TodoRepositoryInterface $todo_repository_interface,
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->line_bot_repository = $line_bot_repository_interface;
        $this->goal_repository = $goal_repository_interface;
        $this->todo_repository = $todo_repository_interface;
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param object $event
     * @return
     */
    public function invoke(object $event, User $line_user)
    {
        foreach ($line_user->todo as $key => $todo) {
            new CarouselColumnTemplateBuilder($todo->name, $todo->name, null, []);
            Log::debug($todo);
            Log::debug($todo->name);
            Log::debug($todo);
        }
        return;
    }
}
