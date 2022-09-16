<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LineUsersQuestion;
use App\Services\LineBotService;
use App\Usecases\Line\FollowAction;
use App\Usecases\Line\MessageReceivedAction;
use App\Repositories\Line\LineBotRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

class LineBotController extends Controller
{
    /**
     * @param LineBotService
     */
    protected $line_bot_service;

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
     * @param App\Repositories\Line\LineBotRepositoryInterface $line_bot_repository_interface
     */
    public function __construct(
        LineBotRepositoryInterface $line_bot_repository_interface
    ) {
        $this->line_bot_service = new LineBotService();
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->line_bot_repository = $line_bot_repository_interface;
    }

    /**
     * When a message is sent to the official Line account,
     * The API is called by LINE WebHook and this method is called.
     *
     * Lineの公式アカウントにメッセージが送られたときに
     * LINE Web HookにてAPIがCallされこのメソッドが呼ばれる
     *
     * @param Request
     */
    public function reply(Request $request, FollowAction $follow_action, MessageReceivedAction $message_received_action)
    {
        // LINEのユーザーIDをuserIdに代入
        $user_id = $request['events'][0]['source']['userId'];

        // ユーザーからフォローされた時
        if ($request['events'][0]['type'] === 'follow') {
            $follow_action->invoke($user_id);
        }
        // ユーザーからメッセージを受け取った時
        if ($request['events'][0]['type'] === 'message') {
            $message_received_action->invoke($request['events'][0]);
        }

        $status_code = $this->line_bot_service->eventHandler($request);

        Log::debug($status_code);
        Log::debug($request['events'][0]);

        return response('', $status_code, []);
    }
}
