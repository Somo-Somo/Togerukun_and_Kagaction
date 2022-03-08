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

    public function invoke($userEmail)
    {

        $getHypothesisListFromDB = $this->hypothesis_repository->getHypothesisList($userEmail);

        $arrayHypothesisList = $getHypothesisListFromDB->toArray();

        //hypothesisListを$hypothesisList[$projectUuid][]などにすればよい
        // 最後のforeachも二重にして同様に行えばいけそう

        // 仮説の一覧を全部ぶち込む配列
        $hypothesisList = [];
        // プロジェクトUUIDをぶち込む配列
        $projectUuidList = [];
        // 仮説一覧の配列とプロジェクトUUIDの配列を合体させる配列
        $projectUuidUnderHypothesisList = [];

        // 親仮説それに紐づく子仮説の順番になるように配列$hypothesisListに追加
        foreach ($arrayHypothesisList as $key => $value) {
            $value = $value->toArray();

            // 親プロジェクト, 親仮説, 子仮説の値を取得
            $projectUuid = $value['project.uuid'];
            $parent = $value['parent']->getProperties()->toArray();
            $childs = $value['collect(child)']->toArray();
            $depth = $value['length(len)'] - 1;
            
            // 仮説のリストからuuidだけ取り出して[$hypothesisListのkey => uuid]の配列の形にしたもの
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

                 // 親仮説でステータスがundifindの場合
                if (!array_keys($parent, 'status')) $parent['status'] =  null;

                $hypothesisList[$projectUuid][] = $parent;
            } 


            // 親に対して複数の子をforeach
            foreach ($childs as $childKey => $childValue) {
                $child = $childValue->getProperties()->toArray();

                // 親仮説のUUID
                $child['parentUuid'] = $parent['uuid'];

                // ゴールからの仮説の階層の深さ
                $child['depth'] = $depth;

                // 子仮説でステータスがundifindの場合
                if (!array_keys($child, 'status')) $child['status'] =  null;

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

            if (!in_array($projectUuid, $projectUuidList)) {
                $projectUuidList[] = $projectUuid;
            }

            $projectArrayKey = array_keys($projectUuidList, $projectUuid);

            $projectUuidUnderHypothesisList[$projectArrayKey[0]] = [
                $projectUuid => $hypothesisList[$projectUuid]
            ];
        }

        return $projectUuidUnderHypothesisList;

        // 旧getの仕方
        // // 仮説の一覧に全部ぶち込む配列
        // $hypothesisList = [];

        // // 親仮説それに紐づく子仮説の順番になるように配列$hypothesisListに追加
        // foreach ($arrayHypothesisList as $key => $value) {
        //     $value = $value->toArray();

        //     // 親プロジェクト, 親仮説, 子仮説の値を取得
        //     $projectUuid = $value['project.uuid'];
        //     $parent = $value['parent']->getProperties()->toArray();
        //     $childs = $value['collect(child)']->toArray();
        //     $depth = $value['length(len)'];

        //     // 仮説のリストからuuidだけ取り出して[$hypothesisListのkey => uuid]の配列の形にしたもの
        //     $uuidList = array_column($hypothesisList, 'uuid');

        //     // 仮説リストにすでに親のUUIDがある場合はそのリストのKEYの番号を返す
        //     $parentKey = array_keys($uuidList, $parent['uuid']);
            
        //     // もし仮説リストに親のUUIDがない場合（親仮説=ゴールの場合のみ）
        //     if (!$parentKey) {
        //         // ゴールは親仮説がいないので親の親UUIDはプロジェクトUUID
        //         $parent['parentUuid'] = $projectUuid;

        //         // ゴールからの仮説の階層の深さ
        //         $parent['depth'] = 0;

        //         $hypothesisList[] = $parent;
        //     } 


        //     // 親に対して複数の子をforeach
        //     foreach ($childs as $childKey => $childValue) {
        //         $child = $childValue->getProperties()->toArray();

        //         // 親仮説のUUID
        //         $child['parentUuid'] = $parent['uuid'];

        //         // ゴールからの仮説の階層の深さ
        //         $child['depth'] = $depth;

        //         // 仮説一覧に親がなかった場合（親=ゴール）
        //         if (!$parentKey) {
        //             //仮説一覧の最後尾に子仮説を追加
        //             $hypothesisList[] = $child;
        //         } else {
        //             // 紐づく親が仮説一覧にない場合(親=ゴール以外)
        //             // 紐づく親仮説の後ろに配列追加
        //             // 第一引数 仮説リスト
        //             // 第二引数 子仮説を仮説リストに追加する位置
        //             // $parentKey = 親仮説のKey番号, 
        //             // $childKey = 親に紐づく子仮説たちのKey
        //             // 第三引数 配列を置き換える要素の数
        //             // 第四引数 追加する配列 = 子仮説
        //             array_splice($hypothesisList, $parentKey + $childKey + 1, 0, $child);
        //         }
        //     }
        // }


        // $formatHypothesisList = [];
        // foreach ($hypothesisList as $value) {
        //     $formatHypothesisList[$value['uuid']] = $value;
        // }

        // return $formatHypothesisList;
    }
}