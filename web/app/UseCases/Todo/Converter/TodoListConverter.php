<?php

namespace App\UseCases\Todo\Converter;

use App\UseCases\Todo\ChildRelateToParentTodo;
use App\UseCases\Comment\Converter\CommentConverter;
use App\UseCases\Cause\Converter\CauseConverter;

class TodoListConverter
{
    protected $child_relate_to_parent_todo;
    protected $comment_converter;
    protected $cause_converter;

    /**
     * @param App\UseCases\Todo\ChildRelateToParentTodo $child_relate_to_parent_todo
     * @param App\UseCases\Comment\Converter\CommentConverter $comment_converter
     * @param App\UseCases\Cause\Converter\CauseConverter $cause_converter
     */
    public function __construct(
        ChildRelateToParentTodo $child_relate_to_parent_todo,
        CommentConverter $comment_converter,
        CauseConverter $cause_converter
    ) {
        $this->child_relate_to_parent_todo = $child_relate_to_parent_todo;
        $this->comment_converter = $comment_converter;
        $this->cause_converter = $cause_converter;
    }

    /**
     * 後で分解する
     *
     * @param object $fetch_project_and_todo_from_neo4j
     * @return array $todo_list
     */
    public function invoke(object $fetch_project_and_todo_from_neo4j)
    {
        $array_todoes = $fetch_project_and_todo_from_neo4j->toArray();

        // 仮説の一覧を全部ぶち込む配列
        $todo_list = [];

        // 仮説を親と紐付けてデータとして保管する配列
        $todo_data = [];


        $left_side_of_line = [];

        // 親仮説それに紐づく子仮説の順番になるように配列$todo_listに追加
        // depth = 1 の場合「ゴール」。parentUuid = project_uuidで保存
        // childはparentUuid = goal にして$todo_dataに保存
        // depth = y > 1　の場合「仮説」。 todoisDataから情報を持ってきてtodo_listの配列に入れる。
        foreach ($array_todoes as $value) {
            $value = $value->toArray();
            // 親プロジェクト, 親仮説, 子仮説の値を取得
            $project = $value['project']->getProperties()->toArray();
            $project_uuid = $project['uuid'];
            $parent = $value['parent']->getProperties()->toArray();
            $childs = $value['collect(child)']->toArray();
            $len = $value['length(len)'];
            $date = $value['date'] ? $value['date']->toArray()['properties']->toArray() : null;
            $fetch_comments = $value['comments'] ? $value['comments']->toArray() : null;
            $comments = $fetch_comments ? $this->comment_converter->invoke($fetch_comments) : null;
            $fetch_causes = $value['causes'] ? $value['causes']->toArray() : null;
            $causes = $fetch_causes ? $this->cause_converter->invoke($fetch_causes) : null;

            if ($childs) {
                // 子どもに親のデータを持たせて$todo_dataに格納。
                // 親になった時にこのtodo_dataからtodo_list仮説一覧配列に格納する
                $todo_data = $this->child_relate_to_parent_todo->invoke($todo_data, $childs, $parent, $len);

                // 子仮説がある場合
                // 仮説にchild: true を持たせる
                $len === 1 ? $parent['child'] = true
                    : $todo_data[$parent['uuid']]['child'] = true;
            } else {
                // 子仮説がない場合
                // 仮説にchild: false を持たせる
                $len === 1 ?
                    $parent['child'] = false : $todo_data[$parent['uuid']]['child'] = false;
            }

            // 仮説 = ゴールの場合
            if ($len === 1) {
                // ゴールは親仮説がいないので親の親UUIDはプロジェクトUUID
                $parent['parentUuid'] = $project_uuid;

                // ゴールからの仮説の階層の深さ
                $parent['depth'] = 0;

                // Todo一覧のテーブルの行の左側の状態
                $left_side_of_line = [];
                $left_side_of_line[] = ['lastChild' => false];
                $parent['leftSideOfLine'] = $left_side_of_line;

                // 日付
                $parent['date'] = $date ? $date['on'] : null;

                // 進捗
                if ($value['accomplish']) $parent['accomplish'] = true;

                // コメント
                $parent['comments'] = $comments ? $comments : [];

                // 原因
                $parent['causes'] = $causes ? $causes : [];

                // ゴールは常に配列のケツに追加
                $todo_list[$project_uuid][] = $parent;
            } else {
                // Todo一覧のテーブルの行の左側の状態
                if (count($left_side_of_line) > $todo_data[$parent['uuid']]['depth']) {
                    $left_side_of_line = array_slice($left_side_of_line, 0, $todo_data[$parent['uuid']]['depth']);
                }
                array_push($left_side_of_line, $todo_data[$parent['uuid']]['lastChildInTheSameDepth']);
                unset($todo_data[$parent['uuid']]['lastChildInTheSameDepth']);
                $todo_data[$parent['uuid']]['leftSideOfLine'] = $left_side_of_line;

                // 日付
                $todo_data[$parent['uuid']]['date'] =  $date ? $date['on'] : null;

                // 完了
                if ($value['accomplish']) $todo_data[$parent['uuid']]['accomplish'] = true;

                // コメント
                $todo_data[$parent['uuid']]['comments'] = $comments ? $comments : [];

                // 原因
                $todo_data[$parent['uuid']]['causes'] = $causes ? $causes : [];

                // $todo_dataから
                $todo_list[$project_uuid][] = $todo_data[$parent['uuid']];
            }
        }

        return $todo_list;
    }
}
