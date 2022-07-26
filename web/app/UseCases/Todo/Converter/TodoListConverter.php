<?php

namespace App\UseCases\Todo\Converter;

use App\UseCases\Todo\Converter\ChildRelateToParentTodoConverter;
use App\UseCases\Todo\Converter\FromObjectToArrayConverter;
use App\UseCases\Todo\Converter\FormatToTypeFrontend;
use App\UseCases\Comment\Converter\CommentConverter;
use App\UseCases\Cause\Converter\CauseConverter;

class TodoListConverter
{
    protected $child_relate_to_parent_todo;
    protected $from_object_to_array;
    protected $format_to_type_frontend;
    protected $comment_converter;
    protected $cause_converter;

    /**
     * @param App\UseCases\Todo\Converter\ChildRelateToParentTodoConverter $child_relate_to_parent_todo
     * @param App\UseCases\Todo\Converter\FromObjectToArrayConverter $from_object_to_array
     * @param App\UseCases\Todo\Converter\FormatToTypeFrontend $format_to_type_frontend
     * @param App\UseCases\Comment\Converter\CommentConverter $comment_converter
     * @param App\UseCases\Cause\Converter\CauseConverter $cause_converter
     */
    public function __construct(
        ChildRelateToParentTodoConverter $child_relate_to_parent_todo,
        FromObjectToArrayConverter $from_object_to_array,
        FormatToTypeFrontend $format_to_type_frontend,
        CommentConverter $comment_converter,
        CauseConverter $cause_converter
    ) {
        $this->child_relate_to_parent_todo = $child_relate_to_parent_todo;
        $this->from_object_to_array = $from_object_to_array;
        $this->format_to_type_frontend = $format_to_type_frontend;
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
            // DBから引っ張ってきたTodoに紐づくデータをオブジェクトから連想配列に
            $todo = $this->from_object_to_array->invoke($value);

            if ($todo['child_todo']) {
                // 子どもに親のデータを持たせて$todo_dataに格納。
                // 親になった時にこのtodo_dataからtodo_list仮説一覧配列に格納する
                $todo_data = $this->child_relate_to_parent_todo->invoke($todo_data, $todo);
            }

            // Todo = ゴールではない場合
            if ($todo['depth'] !== 1) {
                // Todo一覧のテーブルの行の左側の状態
                if (count($left_side_of_line) > $todo_data[$todo['parent_todo']['uuid']]['depth']) {
                    $left_side_of_line = array_slice($left_side_of_line, 0, $todo_data[$todo['parent_todo']['uuid']]['depth']);
                }
                array_push($left_side_of_line, $todo_data[$todo['parent_todo']['uuid']]['lastChildInTheSameDepth']);
                unset($todo_data[$todo['parent_todo']['uuid']]['lastChildInTheSameDepth']);
            }

            $format_todo = $this->format_to_type_frontend->invoke($todo, $todo_data, $left_side_of_line);

            $todo_list[$todo['project']['uuid']][] = $format_todo;
        }
        return $todo_list;
    }
}
