<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Repositories\Todo\TodoRepositoryInterface;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use Illuminate\Support\Facades\Log;

class AddTodo
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
    public function invoke(object $event, User $line_user, string $action_type, string $todo_uuid)
    {
        if ($action_type === 'SELECT_WHETHER_TO_ADD_TODO_OR_HABIT') {
            # code...
        } else if ($action_type === 'ADD_TODO') {
            # code...
        } else if ($action_type === 'ADD_HABIT') {
            # code...
        }

        return;
    }
}
