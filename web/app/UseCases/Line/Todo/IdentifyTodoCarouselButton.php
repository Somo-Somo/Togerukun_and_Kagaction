<?php

namespace App\UseCases\Line\Todo;

use App\Models\User;
use App\Models\Todo;
use App\Services\ComponentBuilder\AddTodoButtonComponentBuilder;
use App\Services\ComponentBuilder\ChangeAndDeleteTodoButtonComponentBuilder;

class IdentifyTodoCarouselButton
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * 受け取ったメッセージを場合分けしていく
     *
     * @param Todo $todo
     * @param string $action_type
     * @return LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder[]
     */
    public function invoke(Todo $todo, string $action_type)
    {
        if ($action_type === 'ALL_TODO_LIST') {
            $actions = ChangeAndDeleteTodoButtonComponentBuilder::createChangeAndDeleteTodoComponentButton($todo);
        } else if ($action_type === 'SHOW_TODO_LIST_TO_ADD_TODO') {
            $actions = AddTodoButtonComponentBuilder::createAddTodoComponentButton($todo);
        }
        return $actions;
    }
}
