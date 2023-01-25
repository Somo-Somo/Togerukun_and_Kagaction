<?php

namespace App\UseCases\Todo\Converter;

use App\UseCases\Comment\Converter\CommentConverter;
use App\UseCases\Cause\Converter\CauseConverter;

class FromObjectToArrayConverter
{
    protected $comment_converter;
    protected $cause_converter;

    /**
     * @param App\UseCases\Comment\Converter\CommentConverter $comment_converter
     * @param App\UseCases\Cause\Converter\CauseConverter $cause_converter
     */
    public function __construct(
        CommentConverter $comment_converter,
        CauseConverter $cause_converter
    ) {
        $this->comment_converter = $comment_converter;
        $this->cause_converter = $cause_converter;
    }

    /**
     * オブジェクトになっている一つのTodoに関連するものを配列化してTodoに連想配列にする
     *
     * @param object $one_column_fetched_from_db
     * @return array $todo
     */
    public function invoke(object $one_column_fetched_from_db)
    {
        $array_of_fetched_column_from_db = $one_column_fetched_from_db->toArray();

        $todo = [];

        $todo['project'] = $array_of_fetched_column_from_db['project']->getProperties()->toArray();
        $todo['parent_todo'] = $array_of_fetched_column_from_db['parent']->getProperties()->toArray();
        $todo['child_todo'] = $array_of_fetched_column_from_db['childs']->toArray();
        $todo['depth'] = intval($array_of_fetched_column_from_db['length(len)']) - 1;
        $todo['date'] = $array_of_fetched_column_from_db['date'] ? $array_of_fetched_column_from_db['date']->toArray()['properties']->toArray() : null;
        $todo['accomplish'] = $array_of_fetched_column_from_db['accomplish'] ? true : false;
        $fetch_comments = $array_of_fetched_column_from_db['comments'] ? $array_of_fetched_column_from_db['comments']->toArray() : null;
        $todo['comments'] = $fetch_comments ? $this->comment_converter->invoke($fetch_comments) : null;
        $fetch_causes = $array_of_fetched_column_from_db['causes'] ? $array_of_fetched_column_from_db['causes']->toArray() : null;
        $todo['causes'] = $fetch_causes ? $this->cause_converter->invoke($fetch_causes) : null;

        return $todo;
    }
}
