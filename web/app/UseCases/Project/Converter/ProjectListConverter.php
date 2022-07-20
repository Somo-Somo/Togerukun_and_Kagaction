<?php

namespace App\UseCases\Project\Converter;

class ProjectListConverter
{
    /**
     * プロジェクトとTodoが入ったオブジェクトを配列に変換
     * プロジェクト一覧の配列にプロジェクトをpush
     *
     * @param object $fetchProjectAndTodoFromNeo4j
     * @return array $projectList
     */
    public function invoke(object $fetchProjectAndTodoFromNeo4j)
    {
        $arrayProjectAndTodo = $fetchProjectAndTodoFromNeo4j->toArray();

        // プロジェクトの一覧を全部ぶち込む配列
        $projectList = [];

        foreach ($arrayProjectAndTodo as $value) {
            $value = $value->toArray();

            $project = $value['project']->getProperties()->toArray();

            // $projectListにプロジェクトの情報がない場合は情報を配列に入れる。
            array_key_exists($project['uuid'], $projectList) ?
                null : $projectList[$project['uuid']] = $project;
        }

        return $projectList;
    }
}
