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
            $len = $value['length(len)'];

            if ($childs) {
                // 子どもに親のデータを持たせて$hypothesisDataに格納。
                // 親になった時にこのhypothesisDataからhypothesisList仮説一覧配列に格納する
                foreach ($childs as $childValue) {
                    $child = $childValue->getProperties()->toArray();

                    // 子仮説の親UUID
                    $child['parentUuid'] = $parent['uuid'];

                    // ゴールからの仮説の階層の深さ
                    $child['depth'] = $len;

                    // 仮説一覧のトグルの状態
                    $child['toggle'] = 'mdi-menu-right';
                    
                    $hypothesisData[$child['uuid']] = $child;
                }
                // 子仮説がある場合
                // 仮説にchild: true を持たせる
                $len === 1 ? $parent['child'] = true 
                    : $hypothesisData[$parent['uuid']]['child'] = true;
            } else {
                // 子仮説がない場合
                // 仮説にchild: false を持たせる
                $len === 1 ? 
                $parent['child'] = false : $hypothesisData[$parent['uuid']]['child'] = false;
            }

            
            // 仮説 = ゴールの場合
            if ($len === 1) {
                // ゴールは親仮説がいないので親の親UUIDはプロジェクトUUID
                $parent['parentUuid'] = $projectUuid;

                // ゴールからの仮説の階層の深さ
                $parent['depth'] = 0;

                // 仮説一覧のトグルの状態
                $parent['toggle'] = 'mdi-menu-right';

                // 現在の目標
                if ($value['currentGoal']) $parent['currentGoal'] = true;
                // 進捗
                if ($value['accomplish']) $parent['accomplish'] = true;

                // ゴールは常に配列のケツに追加
                $hypothesisList[$projectUuid][] = $parent;

            } else {
                // 今日の目標
                if ($value['currentGoal']) $hypothesisData[$parent['uuid']]['currentGoal'] = true;
                // 今日の目標
                if ($value['accomplish']) $hypothesisData[$parent['uuid']]['accomplish'] = true;
                // $hypothesisDataから
                $hypothesisList[$projectUuid][] = $hypothesisData[$parent['uuid']];
            }
        }
        
        return $hypothesisList;
    }
}