<?php

namespace App\UseCases\Todo;

class ChildRelateToParentTodo
{
    public function invoke(array $todoData, array $childs, array $parent, int $len)
    {
        // 子どもに親のデータを持たせて$todoDataに格納。
        // 親になった時にこのtodoDataからtodoList仮説一覧配列に格納する
        foreach ($childs as $key => $childValue) {
            $child = $childValue->getProperties()->toArray();

            // 同じゴールの子で同じ深さの中で一番最後の子Todoか
            $child['lastChildInTheSameDepth'] = $key === 0 ? true : false;

            // 子仮説の親UUID
            $child['parentUuid'] = $parent['uuid'];

            // ゴールからの仮説の階層の深さ
            $child['depth'] = $len;

            // 仮説一覧のトグルの状態
            $child['toggle'] = 'mdi-menu-right';
            
            $todoData[$child['uuid']] = $child;
        }
        return $todoData;
    }
}