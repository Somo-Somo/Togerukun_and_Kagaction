<?php

namespace App\UseCases\Project\Converter;

class ProjectListConverter
{
    public function invoke($fetchProjectAndTodoFromNeo4j)
    {
        $arrayProjectAndHypotheses= $fetchProjectAndTodoFromNeo4j->toArray();

        // プロジェクトの一覧を全部ぶち込む配列
        $projectList = [];

        foreach ($arrayProjectAndHypotheses as $value) {
            $value = $value->toArray();

            $project = $value['project']->getProperties()->toArray();

            // $projectListにプロジェクトの情報がない場合は情報を配列に入れる。
            array_key_exists($project['uuid'], $projectList) ? 
                null : $projectList[$project['uuid']] = $project;
        }

        return $projectList;
    }
}