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
        if ($todo['depth'] === 1) {
            // ゴールは親TODOがないので親UUIDはプロジェクトUUID
            $todo['parent']['parentUuid'] = $todo['project']['uuid'];

            // 日付
            $todo['parent']['date'] = $todo['date'] ? $todo['date']['on'] : null;

            // 完了
            if ($todo['accomplish']) $todo['parent']['accomplish'] = true;

            // 原因
            // 後から原因が追加できるように原因がない場合は空の配列を
            $todo['parent']['causes'] = $todo['causes'] ? $todo['causes'] : [];

            // コメント
            // 後からコメントが追加できるようにコメントがない場合は空の配列を
            $todo['parent']['comments'] = $todo['comments'] ? $todo['comments'] : [];

            // ゴールからのTODOの深層の深さ
            $todo['parent']['depth'] = 0;

            //　子Todoがあるかないか
            $todo['parent']['child'] = $todo['child'] ? true : false;

            //Todo一覧のテーブルの行の左側の状態
            $todo['parent']['leftSideOfLine'][] = ['lastChild' => false];

            return $todo;
        } else {
            // 一覧として配列に格納されている$list_of_left_side_of_lineの個数 = Todoの深さ
            // そのためTodoの深さが$list_of_left_side_of_lineの個数を超えることがないため、
            // 超えている場合はTodoの深さ-1個分切り取る(配列は0から数えるため-1しなくて良い)
            // もしかしたら>じゃなくて>=かもしれないから後で調べる
            // if (count($list_of_left_side_of_line) > $box_storeing_todo[$todo['parent']['uuid']]['depth']) {

            // }
            // TodoツリーのTodoの左側の破線の状態
            $box_storeing_todo[$todo['parent']['uuid']]['leftSideOfLine'] = $left_side_of_line;

            // Todoの日付
            $box_storeing_todo[$todo['parent']['uuid']]['date'] = $todo['date'];

            // Todoの完了有無
            if ($todo['accomplish']) {
                $box_storeing_todo[$todo['parent']['uuid']]['accomplish'] = true;
            }

            // コメント
            $box_storeing_todo[$todo['parent']['uuid']]['comments'] = $todo['comments'] ? $todo['comments'] : [];

            // 原因
            $box_storeing_todo[$todo['parent']['uuid']]['causes'] = $todo['comments'] ? $todo['causes'] : [];

            return $box_storeing_todo;
        }
    }
}
