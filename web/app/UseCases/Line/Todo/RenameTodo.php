<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
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
     * construct
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
     * @return
     */
    public function invoke(object $event, User $line_user, string $todo_uuid)
    {
        $todo = Todo::where('uuid', $todo_uuid);
        if ($event->getType() === 'postback') {
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
        } else if ($event->getType() === 'message') {
            # code...
        }

        return;
    }
}
