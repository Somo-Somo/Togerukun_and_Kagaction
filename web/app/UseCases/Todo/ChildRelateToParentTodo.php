<?php

namespace App\UseCases\Todo;

class ChildRelateToParentTodo
{
    /**
     * 子のTodoをここで親と紐付けて整形する
     *
     * @param array $todoData Todo一覧のデータ
     * @param array $childs 子のTodo
     * @param array $parent 親のTodo
     * @param int $len Todoの深さ
     */
    public function invoke(array $todoData, array $childs, array $parent, int $len)
    {
        // 子どもに親のデータを持たせて$todoDataに格納。
        // 親になった時にこのtodoDataからtodoList仮説一覧配列に格納する
        foreach ($childs as $key => $childValue) {
            $child = $childValue->getProperties()->toArray();

            // 同じゴールの子で同じ深さの中で一番最後の子Todoか
            $child['lastChildInTheSameDepth']['lastChild'] = $key === 0 ? true : false;

            // 子仮説の親UUID
            $child['parentUuid'] = $parent['uuid'];

            // ゴールからの仮説の階層の深さ
            $child['depth'] = $len;

            $todoData[$child['uuid']] = $child;
        }
        return $todoData;
    }
}
