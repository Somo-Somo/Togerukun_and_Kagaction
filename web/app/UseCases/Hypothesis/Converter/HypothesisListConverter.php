<?php

namespace App\UseCases\Hypothesis\Converter;

class HypothesisListConverter
{
    public function invoke($fetchProjectAndHypothesisFromNeo4j)
    {
        $arrayHypotheses= $fetchProjectAndHypothesisFromNeo4j->toArray();

        // 仮説の一覧を全部ぶち込む配列
        $hypothesisList = [];

        // 仮説を親と紐付けてデータとして保管する配列
        $hypothesisData = [];

        // 親仮説それに紐づく子仮説の順番になるように配列$hypothesisListに追加
        // depth = 1 の場合「ゴール」。parentUuid = projectUuidで保存
        // childはparentUuid = goal にして$hypothesisDataに保存
        // depth = y > 1　の場合「仮説」。 hypothesisisDataから情報を持ってきてhypothesisListの配列に入れる。
        foreach ($arrayHypotheses as $value) {
            $value = $value->toArray();

            // 親プロジェクト, 親仮説, 子仮説の値を取得
            $project = $value['project']->getProperties()->toArray();
            $projectUuid = $project['uuid'];
            $parent = $value['parent']->getProperties()->toArray();
            $childs = $value['collect(child)']->toArray();
            $depth = $value['length(len)'];

            // 今日の目標
            if ($value['todaysGoal']) $parent['todaysGoal'] = true;

            if ($childs) {
                // 子どもに親のデータを持たせて$hypothesisDataに格納。
                // 親になった時にこのhypothesisDataからhypothesisList仮説一覧配列に格納する
                foreach ($childs as $childValue) {
                    $child = $childValue->getProperties()->toArray();

                    // 子仮説の親UUID
                    $child['parentUuid'] = $parent['uuid'];

                    // プロジェクトからの仮説の階層の深さ
                    $child['depth'] = $depth + 1;
                    
                    $hypothesisData[$child['uuid']] = $child;
                }
            } else {
                // 子仮説がない場合
                // 仮説にnoChild: true を持たせる
                $depth === 1 ? 
                $parent['noChild'] = true : $hypothesisData[$parent['uuid']]['noChild'] = true;
            }

            
            // 仮説 = ゴールの場合
            if ($depth === 1) {
                // ゴールは親仮説がいないので親の親UUIDはプロジェクトUUID
                $parent['parentUuid'] = $projectUuid;

                // プロジェクトからの仮説の階層の深さ
                $parent['depth'] = $depth;

                // ゴールは常に配列のケツに追加
                $hypothesisList[$projectUuid][] = $parent;

            } else {
                // $hypothesisDataから
                $hypothesisList[$projectUuid][] = $hypothesisData[$parent['uuid']];
            }
        }
        
        return $hypothesisList;
    }
}