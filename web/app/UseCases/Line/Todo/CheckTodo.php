<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Repositories\Date\DateRepositoryInterface;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use DateTime;

class CheckTodo
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
     * @param App\Repositories\Date\DateRepositoryInterface
     */
    protected $todo_repository;

    /**
     * @param App\Repositories\Date\DateRepositoryInterface $todo_repository_interface
     */
    public function __construct(DateRepositoryInterface $todo_repository_interface)
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
        if ($action_type === 'CHECK_TODO_BY_TODAY') {
            # code...
        } else if ($action_type === 'CHECK_TODO_BY_THIS_WEEK') {
            # code...
        } else if ($action_type === 'SELECT_TODO_LIST_TO_CHECK') {
            # code...
        }

        return;
    }
}
