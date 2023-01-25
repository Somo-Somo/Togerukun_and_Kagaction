<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LineBotService;
use App\UseCases\Line\FollowAction;
use App\UseCases\Line\MessageReceivedAction;
use App\UseCases\Line\PostbackReceivedAction;
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
     * @param Request $request
     */
    public function reply(
        Request $request,
        FollowAction $follow_action,
        MessageReceivedAction $message_received_action,
        PostbackReceivedAction $postback_received_action,
    ) {
        $status_code = $this->line_bot_service->eventHandler($request);

        // リクエストをEventオブジェクトに変換する
        $events = $this->bot->parseEventRequest($request->getContent(), $request->header('x-line-signature'));

        foreach ($events as $event) {
            if ($event->getType() === 'follow') {
                $follow_action->invoke($event->getUserId());
            } else if ($event->getType() === 'message') {
                $message_received_action->invoke($event);
            } elseif ($event->getType() === 'postback') {
                $postback_received_action->invoke($event);
            }
        }

        Log::debug($status_code);

        return response('', $status_code, []);
    }
}
