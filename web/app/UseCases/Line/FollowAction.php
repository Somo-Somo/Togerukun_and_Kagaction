<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\LineUsersQuestion;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

class FollowAction
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
     * @param string $line_user_id
     * @return
     */
    public function invoke(string $line_user_id)
    {
        // ユーザーが既に会員登録されているか確認する
        $has_user = User::where('line_user_id', $line_user_id)->first();

        if ($has_user === NULL) {
            $profile = $this->bot->getProfile($line_user_id)->getJSONDecodedBody();

            // Lineユーザーの会員登録を行う
            $user = User::create([
                'name' => $profile['displayName'],
                'uuid' => (string) Str::uuid(),
                'line_user_id' => $line_user_id,
            ]);

            // Lineユーザーへの質問テーブルにも新しくレコードを保存する
            LineUsersQuestion::create([
                'line_user_id' => $line_user_id,
                'question_number' => LineUsersQuestion::PROJECT
            ]);

            // userをneo4jのDBにも登録
            if ($user) {
                $this->user_repository->register($user);
            }
        }
        return;
    }
}
