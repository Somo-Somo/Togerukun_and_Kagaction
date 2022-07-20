<?php

namespace App\UseCases\Project\Converter;

class ProjectListConverter
{
    /**
     * プロジェクトとTodoが入ったオブジェクトを配列に変換
     * プロジェクト一覧の配列にプロジェクトをpush
     *
     * @param object $fetch_project_and_todo_from_neo4j
     * @return array $project_list
     */
    public function invoke(object $fetch_project_and_todo_from_neo4j)
    {
        $array_project_and_todo = $fetch_project_and_todo_from_neo4j->toArray();

        // プロジェクトの一覧を全部ぶち込む配列
        $project_list = [];

        foreach ($array_project_and_todo as $value) {
            $value = $value->toArray();

            $project = $value['project']->getProperties()->toArray();

            // $project_listにプロジェクトの情報がない場合は情報を配列に入れる。
            array_key_exists($project['uuid'], $project_list) ?
                null : $project_list[$project['uuid']] = $project;
        }

        return $project_list;
    }
}
