<?php

namespace App\UseCases\Hypothesis;

use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class StoreAction
{
    protected $hypothesis_repository;

    public function __construct(HypothesisRepositoryInterface $hypothesisRepositoryInterface)
    {
        $this->hypothesis_repository = $hypothesisRepositoryInterface;
    }

    public function invoke(array $hypothesis)
    {

        $getHypothesisListFromDB = $this->hypothesis_repository->create($hypothesis);

        $arrayHypothesisList = $getHypothesisListFromDB->toArray();

        // 仮説の一覧を全部ぶち込む配列
        $hypothesisList = [];

        // プロジェクトの一覧を全部ぶち込む配列
        $projectList = null;

        // 親仮説それに紐づく子仮説の順番になるように配列$hypothesisListに追加
        foreach ($arrayHypothesisList as $value) {
            $value = $value->toArray();

            // 親プロジェクト, 親仮説, 子仮説の値を取得
            $project = $value['project']->getProperties()->toArray();
            $projectUuid = $project['uuid'];
            $parent = $value['parent']->getProperties()->toArray();
            $childs = $value['collect(child)']->toArray();
            $depth = $value['length(len)'];

            // 今日の目標
            if ($value['todaysGoal']) {
                $parent['todaysGoal'] = true;
            }

            // $projectListにプロジェクトの情報がない場合は情報を配列に入れる。
            array_key_exists($projectUuid, $hypothesisList) ?
                null : $projectList = $project;
            
            // 仮説のリストからuuidだけ取り出して[$hypothesisListのkey => uuid]の配列の形にしたもの
            // array_columnで仮設リストのuuidだけ取り出してる
            $uuidList = array_key_exists($projectUuid, $hypothesisList) ?
                array_column($hypothesisList[$projectUuid], 'uuid') : null;

            // 仮説リストにすでに親のUUIDがある場合はそのリストのKEYの番号を返す
            $parentKey = $uuidList ? array_keys($uuidList, $parent['uuid']) : null;
            
            // もし仮説リストに親のUUIDがない場合（親仮説=ゴールの場合のみ）
            if (!$parentKey) {
                // ゴールは親仮説がいないので親の親UUIDはプロジェクトUUID
                $parent['parentUuid'] = $projectUuid;

                // ゴールからの仮説の階層の深さ
                $parent['depth'] = 0;

                $hypothesisList[$projectUuid][] = $parent;
            }


            // 親に対して複数の子をforeach
            foreach ($childs as $childKey => $childValue) {
                $child = $childValue->getProperties()->toArray();

                // 親仮説のUUID
                $child['parentUuid'] = $parent['uuid'];

                // ゴールからの仮説の階層の深さ
                $child['depth'] = $depth;

                // 仮説一覧に親がなかった場合（親=ゴール）
                if (!$parentKey) {
                    //仮説一覧の最後尾に子仮説を追加
                    $hypothesisList[$projectUuid][] = $child;
                } else {
                    // 紐づく親が仮説一覧にない場合(親=ゴール以外)
                    // 紐づく親仮説の後ろに配列追加
                    // 第一引数 仮説リスト
                    // 第二引数 子仮説を仮説リストに追加する位置
                    // $parentKey = 親仮説のKey番号,
                    // $childKey = 親に紐づく子仮説たちのKey
                    // 第三引数 配列を置き換える要素の数
                    // 第四引数 追加する配列 = 子仮説
                    array_splice($hypothesisList[$projectUuid], $parentKey + $childKey + 1, 0, $child);
                }
            }
        }


        $createdHypothesisIsRelatedProjectAndHypothesis = [
            'project' => $projectList,
            'hypothesis' => $hypothesisList,
        ];
        
        return $createdHypothesisIsRelatedProjectAndHypothesis;
    }
}