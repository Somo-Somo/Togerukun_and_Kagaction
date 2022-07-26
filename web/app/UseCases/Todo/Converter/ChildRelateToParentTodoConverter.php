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
        // ここでは親のTodoの一つ下の階層のTodo達がForeachで回されている
        foreach ($todo['child_todo'] as $key => $child_todo) {
            $array_child = $child_todo->getProperties()->toArray();

            // $todo['child_todo']にある子Todoたちは降順である
            // そのため同じ階層の子Todoの中で一番最後のTodo = lastChildになるTodoは
            // Foreachで回した時の最初のTodoすなわちKey = 0　である
            $array_child['lastChildInTheSameDepth']['lastChild'] = $key === 0 ? true : false;

            // 子仮説の親UUID
            $array_child['parentUuid'] = $todo['parent_todo']['uuid'];

            // ゴールからの仮説の階層の深さ
            // この$todoは親のtodoだから+1
            $array_child['depth'] = $todo['depth'] + 1;

            $todo_data[$array_child['uuid']] = $array_child;
        }
        return $todo_data;
    }
}
