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

            // Todoにプロジェクトuuidとdateを合体させる
            $todo['projectUuid'] = $project['uuid'];
            $todo['accomplish'] = $value['accomplished'] ? true : false;
            $todo['date'] = $date['on'];

            array_push($scheduleList, $todo);
        }

        return $scheduleList;
    }
}