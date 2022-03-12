<?php

namespace App\UseCases\Hypothesis\Converter;

class HypothesisListConverter
{
    public function invoke($fetchProjectAndHypothesisFromNeo4j)
    {
        $arrayHypotheses= $fetchProjectAndHypothesisFromNeo4j->toArray();

        // 仮説の一覧を全部ぶち込む配列
        $hypothesisList = [];

        // 親仮説それに紐づく子仮説の順番になるように配列$hypothesisListに追加
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
            
            // すでに同じプロジェクトのキーが仮設一覧に存在していた場合
            // そのプロジェクト内の仮説のUUIDを全て返す
            $uuidList = array_key_exists($projectUuid, $hypothesisList) ? 
                array_column($hypothesisList[$projectUuid], 'uuid') : null;
            

            // すでに仮説一覧($hypotheisList)に仮説が保存されているか探す
            // ある場合（ゴール以外の場合）は既に保存されてるプロジェクト内ののキー（保存されている場所）を返す
            // 子仮説を仮設一覧に入れるときに親仮説の後ろに入れるために使う
            $parentKey = $uuidList ? array_keys($uuidList, $parent['uuid']) : null;
            
            // もし仮説一覧に親のUUIDがない場合（親仮説=ゴールの場合のみ）
            if ($depth === 1) {
                // ゴールは親仮説がいないので親の親UUIDはプロジェクトUUID
                $parent['parentUuid'] = $projectUuid;

                // ゴールからの仮説の階層の深さ
                $parent['depth'] = 0;

                // ゴールは常に配列のケツに追加
                $hypothesisList[$projectUuid][] = $parent;
            } 


            // 親に紐づく複数の子をforeach
            foreach ($childs as $childKey => $childValue) {
                $child = $childValue->getProperties()->toArray();

                // 子仮説の親UUID
                $child['parentUuid'] = $parent['uuid'];

                // ゴールからの仮説の階層の深さ
                $child['depth'] = $depth;

                // 親仮説がゴールだった場合
                if ($depth === 1) {
                    //同プロジェクト内の仮説一覧の最後尾に子仮説を追加
                    $hypothesisList[$projectUuid][] = $child;
                } 
                // 親仮説がゴールではなかった場合
                else {
                    // array_spliceで全部の値をforeachされてしまうのでもう一個配列を被せる
                    $addChild = ['child' => $child];
                    // 紐づく親が仮説一覧にない場合(親=ゴール以外)
                    // 紐づく親仮説の後ろに配列追加
                    // 第一引数 仮説リスト
                    // 第二引数 子仮説を仮説リストに追加する位置
                    // $parentKey = 親仮説のKey番号, 
                    // $childKey = 親に紐づく子仮説たちのKey
                    // 第三引数 配列を置き換える要素の数
                    // 第四引数 追加する配列 = 子仮説
                    array_splice($hypothesisList[$projectUuid], (int)$parentKey + (int)$childKey + 1, 0, $addChild);
                }
            }
        }
        return $hypothesisList;
    }
}