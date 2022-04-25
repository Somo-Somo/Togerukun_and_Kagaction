<?php

namespace App\UseCases\Date\Converter;

class ScheduleListConverter
{
    public function invoke($fetchTodoAndDate)
    {
        $arrayTodoAndDate = $fetchTodoAndDate->toArray();

        $scheduleList = [];

        foreach ($arrayTodoAndDate as $value) {
            // プロジェクト, Todo, Dateを取得
            $value = $value->toArray();
            $project = $value['project']->getProperties()->toArray();
            $todo = $value['hypothesis']->getProperties()->toArray();
            $date = $value['date']->getProperties()->toArray();
            $parent = $value['parent'] ? $value['parent']->getProperties()->toArray() : null;
            $len = $value['length(len)'];
            $child = $value['child'];

            // Todoにプロジェクトuuidとdateを合体させる
            $todo['projectUuid'] = $project['uuid'];
            $todo['accomplish'] = $value['accomplished'] ? true : false;
            $todo['date'] = $date['on'];
            $todo['parentUuid'] = $parent ? $parent['uuid'] : $project['uuid'];
            $todo['depth'] = (int)$len - 1;
            $todo['child'] = $child ? true : false;
            if($child) $todo['toggle'] = 'mdi-menu-right';

            array_push($scheduleList, $todo);
        }

        return $scheduleList;
    }
}