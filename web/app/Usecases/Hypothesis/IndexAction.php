<?php

namespace App\UseCases\Hypothesis;

use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class IndexAction
{
    protected $hypothesis_repository;

    public function __construct(HypothesisRepositoryInterface $hypothesisRepositoryInterface)
    {
        $this->hypothesis_repository = $hypothesisRepositoryInterface;
    }

    public function invoke($projectUuid)
    {

        $getHypothesisListFromDB = $this->hypothesis_repository->getHypothesisList($projectUuid);

        $arrayHypothesisList = $getHypothesisListFromDB->toArray();

        // 仮説のリスト全部ぶち込む配列
        $hypothesisList = [];

        // 親仮説それに紐づく子仮説の順番になるように配列$hypothesisListに追加
        foreach ($arrayHypothesisList as $key => $value) {
            $value = $value->toArray();

            // 親プロジェクト, 親仮説, 子仮説の値を取得
            $projectUuid = $value['project.uuid'];
            $parent = $value['parent']->getProperties()->toArray();
            $childs = $value['collect(child)']->toArray();
            $depth = $value['length(len)'];

            // 仮説のリストからuuidだけ取り出して[$hypothesisListのkey => uuid]の配列の形にしたもの
            $uuidList = array_column($hypothesisList, 'uuid');

            // 仮説リストにすでに親のUUIDがある場合はそのリストのKEYの番号を返す
            $parentKey = array_keys($uuidList, $parent['uuid']);
            
            // もし仮説リストに親のUUIDがない場合（親仮説=ゴールの場合のみ）
            if (!$parentKey) {
                // ゴールは親仮説がいないので親UUIDはプロジェクトUUID
                $parent['parentUuid'] = $projectUuid;
                // ゴールからの仮説の階層の深さ
                $parent['depth'] = 0;
                $hypothesisList[] = $parent;

                // 親に対して複数の子をforeach
                foreach ($childs as $childValue) {
                    $child = $childValue->getProperties()->toArray();

                    // 親仮説のUUID
                    $child['parentUuid'] = $parent['uuid'];

                    // ゴールからの仮説の階層の深さ
                    $child['depth'] = $depth;

                    //仮説リストの最後尾に子仮説を追加
                    $hypothesisList[] = $child;
                }
            } else {
                // もし仮説リストに親のUUIDがあった場合（親仮説=ゴール以外の場合）
                foreach ($childs as $childKey => $childValue) {
                    $child = $childValue->getProperties()->toArray();

                    // 親仮説のUUID
                    $child['parentUuid'] = $parent['uuid'];                    

                    // ゴールからの仮説の階層の深さ
                    $child['depth'] = $depth;

                    // 紐づく親仮説の後ろに配列追加
                    // 第一引数 仮説リスト
                    // 第二引数 子仮説を仮説リストに追加する位置
                    // $parentKey = 親仮説のKey番号, 
                    // $childKey = 親に紐づく子仮説たちのKey
                    // 第三引数 配列を置き換える要素の数
                    // 第四引数 追加する配列 = 子仮説
                    array_splice($hypothesisList, $parentKey + $childKey + 1, 0, $child);
                }
            }
        }

        return $hypothesisList;
    }
}