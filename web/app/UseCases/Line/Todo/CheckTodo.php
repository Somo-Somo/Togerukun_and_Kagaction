<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Models\LineUsersQuestion;
use App\Repositories\Date\DateRepositoryInterface;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
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
        // Todoセレクト前
        if (!$todo_uuid) {
            $today_date_time = new DateTime();
            $today = $today_date_time->format('Y-m-d');
            if ($action_type === 'CHECK_TODO_BY_TODAY') {
                $todo_list = Todo::where('user_uuid', $line_user->uuid)
                    ->where('date', $today)->get();
            } elseif ($action_type === 'CHECK_TODO_BY_THIS_WEEK') {
                $next_week = $today_date_time->modify('+1 week')->format('Y-m-d');
                $todo_list = Todo::where('user_uuid', $line_user->uuid)
                    ->whereBetween('date', [$today, $next_week])
                    ->get();
                Log::debug($todo_list);
            } elseif ($action_type === 'SELECT_TODO_LIST_TO_CHECK') {
                $todo_list = $line_user->todo;
            }

            $todo_carousel_columns = [];
            foreach ($todo_list as $todo) {
                if (count($todo->accomplish) === 0) {
                    $todo_carousel_columns[] = Todo::createCheckTodoCarouselColumn($todo);
                }
            }

            $over_due_todo_list = Todo::where('user_uuid', $line_user->uuid)
                ->where('date', '<', $today);
            foreach ($over_due_todo_list as $over_due_todo) {
                if ($over_due_todo->accomplish === null) {
                    $todo_carousel_columns[] = Todo::createCheckTodoCarouselColumn($over_due_todo);
                }
            }

            $message = Todo::createTodoListTitleMessage($line_user, $action_type, $todo_carousel_columns);
            // 該当のTodoがある場合
            if (count($todo_carousel_columns) > 0) {
                $todo_carousels = new CarouselTemplateBuilder($todo_carousel_columns);
                $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                $builder->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message['text']));
                $builder->add(new TemplateMessageBuilder('振り返り', $todo_carousels));
            } else {
                $builder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message['text']);
            }
            $this->bot->replyMessage(
                $event->getReplyToken(),
                $builder
            );

            // なんの振り返りをしているか記憶しておく
            $line_user->quetion->update([
                'checked_todo',
                LineUsersQuestion::CHECK_TODO[$action_type]
            ]);
        } else {
            $todo = Todo::where('uuid' . $todo_uuid)->first();
            if ($action_type === 'CHECK_TODO') {
                $builder = new TemplateMessageBuilder('振り返り', Todo::askIfTodoHasBeenAccomplished($todo));
            } else if ($action_type === 'ACOMMPLISHED_TODO') {
                $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                $builder->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('「' . $todo->name . '」の達成おめでとうございます！'));
                $builder->add(new TemplateMessageBuilder('振り返り', Todo::askContinueCheckTodo($line_user->question, $action_type)));
            } else if ($action_type === 'NOT_ACOMMPLISHED_TODO') {
                $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                $builder->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('引き続き「' . $todo->name . '」の達成に向けて頑張っていきましょう！'));
                $builder->add(new TemplateMessageBuilder('やることの追加', Todo::addTodoAfterCheckTodo($todo)));
            } else if ($action_type === 'ADD_TODO_AFTER_CHECK_TODO') {
                $builder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder(Todo::askTodoName($todo));
            } else if ($action_type === 'NOT_ADD_TODO_AFTER_CHECK_TODO') {
                $builder = new TemplateMessageBuilder('振り返り', Todo::askContinueCheckTodo($line_user->question, $action_type));
            }
            $this->bot->replyMessage(
                $event->getReplyToken(),
                $builder
            );
        }

        return;
    }
}
