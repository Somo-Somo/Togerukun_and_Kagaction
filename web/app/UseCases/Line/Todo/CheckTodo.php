<?php

namespace App\UseCases\Line\Todo;

use App\Models\AccomplishTodo;
use App\Models\User;
use App\Models\Todo;
use App\Models\CheckedTodo;
use App\Models\LineUsersQuestion;
use App\Repositories\Todo\TodoRepositoryInterface;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
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
        if ($action_type === 'FINISH_CHECK_TODO') {
            $message = CheckedTodo::getTextMessageOfFinishCheckTodo($line_user->question);
            $this->bot->replyText($event->getReplyToken(), $message);
            $line_user->question->update([
                'checked_todo' => null,
                'parent_uuid' => null,
            ]);
            return;
        }
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
            } elseif ($action_type === 'SELECT_TODO_LIST_TO_CHECK') {
                $todo_list = $line_user->todo;
            }

            $todo_carousel_columns = [];
            foreach ($todo_list as $todo) {
                if (count($todo->accomplish) === 0) {
                    $todo_carousel_columns[] = Todo::createBubbleContainer($todo, $action_type);
                }
            }

            $over_due_todo_list = Todo::where('user_uuid', $line_user->uuid)
                ->where('date', '<', $today);
            foreach ($over_due_todo_list as $over_due_todo) {
                if ($over_due_todo->accomplish === null) {
                    $todo_carousel_columns[] = Todo::createBubbleContainer($over_due_todo, $action_type);
                }
            }
            $message = Todo::createTodoListTitleMessage($line_user, $action_type, $todo_carousel_columns);

            // 該当のTodoがある場合
            if (count($todo_carousel_columns) > 0) {
                $todo_carousels = new CarouselContainerBuilder($todo_carousel_columns);
                $flex_message = new FlexMessageBuilder(
                    'やること一覧',
                    $todo_carousels
                );
                $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                $builder->add(
                    new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message['text'])
                );
                $builder->add($flex_message);
            } else {
                $builder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message['text']);
            }
            $this->bot->replyMessage(
                $event->getReplyToken(),
                $builder
            );

            // なんの振り返りをしているか記憶しておく
            $line_user->question->update([
                'checked_todo' => CheckedTodo::CHECK_TODO[$action_type]
            ]);
        } else {
            $todo = Todo::where('uuid', $todo_uuid)->first();
            if ($action_type === 'CHECK_TODO') {
                $builder = new TemplateMessageBuilder('振り返り', CheckedTodo::askIfTodoHasBeenAccomplished($todo));
                $this->bot->replyMessage($event->getReplyToken(), $builder);
            } else if ($action_type === 'ACCOMPLISHED_TODO') {
                $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                $builder->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('「' . $todo->name . '」の達成おめでとうございます！'));
                $builder->add(new TemplateMessageBuilder('振り返り', CheckedTodo::askContinueCheckTodo($line_user->question)));
                $this->bot->replyMessage($event->getReplyToken(), $builder);
                AccomplishTodo::updateOrCreate(
                    ['todo_uuid' => $todo_uuid],
                    ['user_uuid' => $line_user->uuid]
                );
                $this->todo_repository->updateAccomplish(
                    ['uuid' => $todo_uuid, 'user_uuid' => $line_user->uuid]
                );
            } else if ($action_type === 'NOT_ACCOMPLISHED_TODO') {
                $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                $builder->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('引き続き「' . $todo->name . '」の達成に向けて頑張っていきましょう！'));
                $builder->add(new TemplateMessageBuilder('やることの追加', CheckedTodo::addTodoAfterCheckTodo($todo)));
                $this->bot->replyMessage($event->getReplyToken(), $builder);;
            } else if ($action_type === 'ADD_TODO_AFTER_CHECK_TODO') {
                $builder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder(Todo::askTodoName($todo));
                $this->bot->replyMessage($event->getReplyToken(), $builder);
                // なんのTodoのためのTodoを追加するか記憶しておく
                $line_user->question->update([
                    'question_number' => LineUsersQuestion::TODO,
                    'parent_uuid' => $todo->uuid,
                ]);
            } else if ($action_type === 'NOT_ADD_TODO_AFTER_CHECK_TODO') {
                $builder = new TemplateMessageBuilder('振り返り', CheckedTodo::askContinueCheckTodo($line_user->question, $action_type));
                $this->bot->replyMessage($event->getReplyToken(), $builder);
            }
        }
        return;
    }
}
