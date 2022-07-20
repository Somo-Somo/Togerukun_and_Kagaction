<?php

namespace App\UseCases\Todo\Converter;

use App\UseCases\Todo\ChildRelateToParentTodo;
use App\UseCases\Comment\Converter\CommentConverter;
use App\UseCases\Cause\Converter\CauseConverter;

class TodoListConverter
{
    protected $childRelateToParentTodo;
    protected $commentConverter;

    /**
     * @param App\UseCases\Todo\ChildRelateToParentTodo $childRelateToParentTodo
     * @param App\UseCases\Comment\Converter\CommentConverter $commentConverter
     * @param App\UseCases\Cause\Converter\CauseConverter $causeConverter
     */
    public function __construct(
        ChildRelateToParentTodo $childRelateToParentTodo,
        CommentConverter $commentConverter,
        CauseConverter $causeConverter
    ) {
        $this->childRelateToParentTodo = $childRelateToParentTodo;
        $this->commentConverter = $commentConverter;
        $this->causeConverter = $causeConverter;
    }

    /**
     * 後で分解する
     *
     * @param object $fetchProjectAndTodoFromNeo4j
     * @return array $todoList
     */
    public function invoke(object $fetchProjectAndTodoFromNeo4j)
    {
        $arrayTodoes = $fetchProjectAndTodoFromNeo4j->toArray();

        // 仮説の一覧を全部ぶち込む配列
        $todoList = [];

        // 仮説を親と紐付けてデータとして保管する配列
        $todoData = [];


        $leftSideOfLine = [];

        // 親仮説それに紐づく子仮説の順番になるように配列$todoListに追加
        // depth = 1 の場合「ゴール」。parentUuid = projectUuidで保存
        // childはparentUuid = goal にして$todoDataに保存
        // depth = y > 1　の場合「仮説」。 todoisDataから情報を持ってきてtodoListの配列に入れる。
        foreach ($arrayTodoes as $value) {
            $value = $value->toArray();
            // 親プロジェクト, 親仮説, 子仮説の値を取得
            $project = $value['project']->getProperties()->toArray();
            $projectUuid = $project['uuid'];
            $parent = $value['parent']->getProperties()->toArray();
            $childs = $value['collect(child)']->toArray();
            $len = $value['length(len)'];
            $date = $value['date'] ? $value['date']->toArray()['properties']->toArray() : null;
            $fetchComments = $value['comments'] ? $value['comments']->toArray() : null;
            $comments = $fetchComments ? $this->commentConverter->invoke($fetchComments) : null;
            $fetchCauses = $value['causes'] ? $value['causes']->toArray() : null;
            $causes = $fetchCauses ? $this->causeConverter->invoke($fetchCauses) : null;

            if ($childs) {
                // 子どもに親のデータを持たせて$todoDataに格納。
                // 親になった時にこのtodoDataからtodoList仮説一覧配列に格納する
                $todoData = $this->childRelateToParentTodo->invoke($todoData, $childs, $parent, $len);

                // 子仮説がある場合
                // 仮説にchild: true を持たせる
                $len === 1 ? $parent['child'] = true
                    : $todoData[$parent['uuid']]['child'] = true;
            } else {
                // 子仮説がない場合
                // 仮説にchild: false を持たせる
                $len === 1 ?
                    $parent['child'] = false : $todoData[$parent['uuid']]['child'] = false;
            }

            // 仮説 = ゴールの場合
            if ($len === 1) {
                // ゴールは親仮説がいないので親の親UUIDはプロジェクトUUID
                $parent['parentUuid'] = $projectUuid;

                // ゴールからの仮説の階層の深さ
                $parent['depth'] = 0;

                // Todo一覧のテーブルの行の左側の状態
                $leftSideOfLine = [];
                $leftSideOfLine[] = ['lastChild' => false];
                $parent['leftSideOfLine'] = $leftSideOfLine;

                // 日付
                $parent['date'] = $date ? $date['on'] : null;

                // 進捗
                if ($value['accomplish']) $parent['accomplish'] = true;

                // コメント
                $parent['comments'] = $comments ? $comments : [];

                // 原因
                $parent['causes'] = $causes ? $causes : [];

                // ゴールは常に配列のケツに追加
                $todoList[$projectUuid][] = $parent;
            } else {
                // Todo一覧のテーブルの行の左側の状態
                if (count($leftSideOfLine) > $todoData[$parent['uuid']]['depth']) {
                    $leftSideOfLine = array_slice($leftSideOfLine, 0, $todoData[$parent['uuid']]['depth']);
                }
                array_push($leftSideOfLine, $todoData[$parent['uuid']]['lastChildInTheSameDepth']);
                unset($todoData[$parent['uuid']]['lastChildInTheSameDepth']);
                $todoData[$parent['uuid']]['leftSideOfLine'] = $leftSideOfLine;

                // 日付
                $todoData[$parent['uuid']]['date'] =  $date ? $date['on'] : null;

                // 完了
                if ($value['accomplish']) $todoData[$parent['uuid']]['accomplish'] = true;

                // コメント
                $todoData[$parent['uuid']]['comments'] = $comments ? $comments : [];

                // 原因
                $todoData[$parent['uuid']]['causes'] = $causes ? $causes : [];

                // $todoDataから
                $todoList[$projectUuid][] = $todoData[$parent['uuid']];
            }
        }

        return $todoList;
    }
}
