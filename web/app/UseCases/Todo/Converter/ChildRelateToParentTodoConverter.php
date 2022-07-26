<?php

namespace App\UseCases\Todo\Converter;

class ChildRelateToParentTodoConverter
{
    /**
     * 子のTodoをここで親と紐付けて整形する
     *
     * @param array $todo_data Todo一覧のデータ
     * @param array $todo 子のTodo
     */
    public function invoke(array $todo_data, array $todo)
    {
        // 子どもに親のデータを持たせて$todo_dataに格納。
        // 親になった時にこのtodo_dataからtodoList仮説一覧配列に格納する
        foreach ($todo['child_todo'] as $key => $child_todo) {
            $child = $child_todo->getProperties()->toArray();

            // 同じゴールの子で同じ深さの中で一番最後の子Todoか
            $child['lastChildInTheSameDepth']['lastChild'] = $key === 0 ? true : false;

            // 子仮説の親UUID
            $child['parentUuid'] = $todo['parent_todo']['uuid'];

            // ゴールからの仮説の階層の深さ
            $child['depth'] = $todo['depth'];

            $todo_data[$child['uuid']] = $child;
        }
        return $todo_data;
    }
}
