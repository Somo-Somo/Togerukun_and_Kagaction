<?php

namespace App\UseCases\Date\Converter;

use App\UseCases\Comment\Converter\CommentConverter;

class ScheduleListConverter
{
    protected $comment_converter;

    /**
     * @param App\UseCases\Comment\Converter\CommentConverter $comment_converter
     */
    public function __construct(CommentConverter $comment_converter)
    {
        $this->comment_converter = $comment_converter;
    }

    /**
     * 取得した日付が付随するTodoたちを展開して一つの配列に収納
     *
     * @param object $fetch_todo_and_date
     * @return array $schedule_list
     */
    public function invoke(object $fetch_todo_and_date)
    {
        $array_todo_and_date = $fetch_todo_and_date->toArray();

        $schedule_list = [];

        foreach ($array_todo_and_date as $value) {
            // プロジェクト, Todo, Dateを取得
            $value = $value->toArray();
            $project = $value['project']->getProperties()->toArray();
            $todo = $value['todo']->getProperties()->toArray();
            $date = $value['date']->getProperties()->toArray();
            $parent = $value['parent'] ? $value['parent']->getProperties()->toArray() : null;
            $len = $value['length(len)'];
            $child = $value['collect(child)'];
            $fetch_comments = $value['comments'] ? $value['comments']->toArray() : null;
            $comments = $fetch_comments ? $this->comment_converter->invoke($fetch_comments) : null;

            // Todoにプロジェクトuuidとdateを合体させる
            $todo['projectUuid'] = $project['uuid'];
            $todo['accomplish'] = $value['accomplished'] ? true : false;
            $todo['date'] = $date['on'];
            $todo['parentUuid'] = $parent ? $parent['uuid'] : $project['uuid'];
            $todo['depth'] = (int)$len - 1;
            $todo['child'] = $child ? true : false;
            $todo['comments'] = $comments ? $comments : [];
            if ($child) $todo['toggle'] = 'mdi-menu-right';

            array_push($schedule_list, $todo);
        }

        return $schedule_list;
    }
}
