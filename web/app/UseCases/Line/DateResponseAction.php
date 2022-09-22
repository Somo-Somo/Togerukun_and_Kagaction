<?php

namespace App\UseCases\Line;

use App\Models\User;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use App\Models\Onboarding;
use App\Repositories\Date\DateRepositoryInterface;
use App\Repositories\Line\LineBotRepositoryInterface;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use DateTime;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

class DateResponseAction
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
     * @param App\Repositories\Todo\DateRepositoryInterface
     */
    protected $date_repository;

    /**
     * @param App\Repositories\Line\LineRepositoryInterface $line_repository_interface
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     */
    public function __construct(
        LineBotRepositoryInterface $line_bot_repository_interface,
        DateRepositoryInterface $date_repository_interface,
    ) {
        $this->httpClient = new CurlHTTPClient(config('app.line_channel_access_token'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => config('app.line_channel_secret')]);
        $this->line_bot_repository = $line_bot_repository_interface;
        $this->date_repository = $date_repository_interface;
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param object $event
     * @param User $line_user
     * @param string $uuid_value
     * @return
     */
    public function invoke(object $event, User $line_user, string $uuid_value)
    {
        // 日付に関する質問の場合
        $date = [
            'uuid' => $uuid_value,
            'user_uuid' => $line_user->uuid,
            'date' => $event->getPostbackParams()['date']
        ];

        // 紐づいているTodo
        $todo = Todo::where('uuid', $uuid_value)->first();

        // オンボーディングが終わっているか確認
        $not_completed_onboarding = Onboarding::where('user_uuid', $line_user->uuid)->first();

        if ($not_completed_onboarding) {
            // オンボーディングが終わっていない場合
            $this->bot->replyText(
                $event->getReplyToken(),
                Todo::confirmDate($todo, new DateTime($date['date'])),
                Todo::callForAdditionalTodo(),
                Todo::explainSettingOfCheck()
            );
            $not_completed_onboarding->delete();
        } else {
            $parent_todo = Todo::where('uuid', $todo->parent_uuid)->first();
            $carousel_columns = [
                Todo::continueAddTodoOfTodo($todo),
                Todo::continueAddTodoOfParentTodo($parent_todo),
                Todo::comeBackTodoList($todo->project)
            ];
            $carousel = new CarouselTemplateBuilder($carousel_columns);
            $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
            $builder->add(new TextMessageBuilder(Todo::confirmDate($todo, new DateTime($date['date']))));
            $builder->add(new TemplateMessageBuilder('選択', $carousel));
            $this->bot->replyMessage(
                $event->getReplyToken(),
                $builder
            );
        }

        // Todoに日付の期限を授ける
        Todo::where('uuid', $date['uuid'])
            ->update(['date' => $date['date']]);

        // 質問の更新
        $line_user->question->update([
            'question_number' => LineUsersQuestion::NO_QUESTION,
            'parent_uuid' => null
        ]);

        $this->date_repository->updateDate($date);
    }
}
