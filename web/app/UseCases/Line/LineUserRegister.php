<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

class LineUserRegister
{
    /**
     * @param LINE\LINEBot\HTTPClient\CurlHTTPClient
     */
    protected $httpClient;

    /**
     * @param LINE\LINEBot
     */
    protected $bot;

    public function __construct(UserRepositoryInterface $user_repository_interface)
    {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->user_repository = $user_repository_interface;
    }

    /**
     * ユーザーが会員登録されているか確認する
     *
     * @param
     * @return
     */
    public function invoke($user_id)
    {
        $profile = $this->bot->getProfile($user_id)->getJSONDecodedBody();
        // userの会員登録が行われていない場合
        $user = User::create([
            'name' => $profile['displayName'],
            'uuid' => (string) Str::uuid(),
            'line_user_id' => $user_id,
        ]);
        // ユーザー作成後UserRepositoryを通してNeo4jに保存
        $this->user_repository->register($user);
        Log::debug($user);
    }
}
