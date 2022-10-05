<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use App\Repositories\Todo\TodoRepositoryInterface;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

use function Psy\debug;

class RenameTodo
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
     * @param App\Repositories\Todo\TodoRepositoryInterface
     */
    protected $todo_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface $todo_repository_interface
     */
    public function __construct(TodoRepositoryInterface $todo_repository_interface)
    {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->todo_repository = $todo_repository_interface;
    }

    /**
     * Todoの名前を変更する
     *
     * @param object $event
     * @param User $line_user
     * @param string $todo_uuid
     * @return
     */
    public function invoke(object $event, User $line_user, string $todo_uuid)
    {
        $todo = Todo::where('uuid', $todo_uuid)->first();
        if ($event->getType() === 'postback') { // 名前の変更を聞く
            // 返信メッセージ
            $this->bot->replyText(
                $event->getReplyToken(),
                Todo::askTodoReName($todo)
            );
            //質問の更新
            $line_user->question->update([
                'question_number' => LineUsersQuestion::RENAME_TODO,
                'parent_uuid' => $todo->uuid,
                'project_uuid' => $todo->project->uuid
            ]);
        } else if ($event->getType() === 'message') { // 名前の変更を完了する
            $new_todo_name =  $event->getText();
            // 返信メッセージ
            $this->bot->replyText(
                $event->getReplyToken(),
                Todo::reportNewTodoName($todo, $new_todo_name)
            );

            // 名前の更新
            $todo->update([
                'name' => $new_todo_name
            ]);

            //質問の更新
            $line_user->question->update([
                'question_number' => LineUsersQuestion::NO_QUESTION,
                'parent_uuid' => null
            ]);

            $this->todo_repository->update([
                'name' => $new_todo_name,
                'uuid' => $todo_uuid,
                'user_uuid' => $line_user->uuid
            ]);
        }

        return;
    }
}
