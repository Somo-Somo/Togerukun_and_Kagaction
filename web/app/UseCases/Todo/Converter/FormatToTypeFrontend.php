<?php

namespace App\UseCases\Todo\Converter;

class FormatToTypeFrontend
{
    /**
     * オブジェクトになっている一つのTodoに関連するものを配列化してTodoに連想配列にする
     *
     * @param array $todo
     * @param array $box_storeing_todo Todoが連想配列化された後に収納されるTodoの配列
     * @param array $list_of_left_side_of_line フロントエンド側で表示されるTodoの左側にある破線が有るかないかの状態の一覧
     * @return array $todo
     */
    public function invoke(array $todo, array $box_storeing_todo, array $left_side_of_line)
    {
        // Todo = ゴールの場合
        if ($todo['depth'] === 0) {
            // ゴールは親TODOがないので親UUIDはプロジェクトUUID
            $todo['parent_todo']['parentUuid'] = $todo['project']['uuid'];

            // 日付
            $todo['parent_todo']['date'] = $todo['date'] ? $todo['date']['on'] : null;

            // 完了
            if ($todo['accomplish']) $todo['parent_todo']['accomplish'] = true;

            // 原因
            // 後から原因が追加できるように原因がない場合は空の配列を
            $todo['parent_todo']['causes'] = $todo['causes'] ? $todo['causes'] : [];

            // コメント
            // 後からコメントが追加できるようにコメントがない場合は空の配列を
            $todo['parent_todo']['comments'] = $todo['comments'] ? $todo['comments'] : [];

            // ゴールからのTODOの深層の深さ
            $todo['parent_todo']['depth'] = $todo['depth'];

            //　子Todoがあるかないか
            $todo['parent_todo']['child'] = $todo['child_todo'] ? true : false;

            //Todo一覧のテーブルの行の左側の状態
            $todo['parent_todo']['leftSideOfLine'] = $left_side_of_line;

            return $todo['parent_todo'];
        } else {
            // Todoの日付
            $box_storeing_todo[$todo['parent_todo']['uuid']]['date'] = $todo['date'] ? $todo['date']['on'] : null;

            // Todoの完了有無
            if ($todo['accomplish']) {
                $box_storeing_todo[$todo['parent_todo']['uuid']]['accomplish'] = true;
            }

            // 原因
            $box_storeing_todo[$todo['parent_todo']['uuid']]['causes'] = $todo['comments'] ? $todo['causes'] : [];

            // コメント
            $box_storeing_todo[$todo['parent_todo']['uuid']]['comments'] = $todo['comments'] ? $todo['comments'] : [];

            // 子Todoがあるかないか
            $box_storeing_todo[$todo['parent_todo']['uuid']]['child'] = $todo['child_todo'] ? true : false;

            // TodoツリーのTodoの左側の破線の状態
            $box_storeing_todo[$todo['parent_todo']['uuid']]['leftSideOfLine'] = $left_side_of_line;

            return $box_storeing_todo[$todo['parent_todo']['uuid']];
        }
    }
}
