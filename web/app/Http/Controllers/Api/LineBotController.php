<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LineBotService;
use App\Usecases\Line\LineUserRegister;
use App\Repositories\User\UserRepositoryInterface;
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
     * @param App\Repositories\User\UserRepositoryInterface
     */
    protected $user_repository;

    public function __construct(UserRepositoryInterface $user_repository_interface)
    {
        $this->line_bot_service = new LineBotService();
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->user_repository = $user_repository_interface;
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
    public function reply(Request $request, LineUserRegister $line_user_register)
    {
        // Requestが来たかどうか確認する
        $content = 'Request from LINE';
        $param_str = json_encode($request->all());
        $log_message =
            <<<__EOM__
        $content
        $param_str
        __EOM__;

        // LINEのユーザーIDをuserIdに代入
        $user_id = $request['events'][0]['source']['userId'];

        // userIdがあるユーザーを検索
        $user = User::where('line_user_id', $user_id)->first();

        if ($user === NULL) {
            $line_user_register->invoke($user_id);
        }

        Log::debug($user_id);

        $status_code = $this->line_bot_service->eventHandler($request);

        return response('', $status_code, []);
    }
}
