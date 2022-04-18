<?php

namespace App\Repositories\User;

use App\Facades\Neo4jDB;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function register($user)
    {
        $createUser = $this->client->run(
            <<<'CYPHER'
                CREATE (
                    user:User {
                        user_id: $user_id,
                        name: $name, 
                        uuid: $uuid, 
                        email: $email,
                        password: $password,
                        created_at: localdatetime({timezone: 'Asia/Tokyo'})
                    })
                RETURN user
                CYPHER,
                [
                    'user_id' => $user['id'], 
                    'uuid' => $user['uuid'], 
                    'name' => $user['name'], 
                    'email' => $user['email'],
                    'password' => $user['password']
                ]);
        return $createUser;
    }

    public function getUserHasProjetAndHypothesis(string $user_email)
    {
        $userHasProjetAndHypothesis = $this->client->run(
            <<<'CYPHER'
                MATCH (user:User{email:$user_email}) - [:HAS] -> (project:Project),
                    len = (project) <- [r*] - (parent:Hypothesis)
                OPTIONAL MATCH (parent)<-[]-(child:Hypothesis)
                OPTIONAL MATCH (:User)-[currentGoal:SET_CURRENT_GOAL]->(parent)
                OPTIONAL MATCH (:User)-[accomplish:ACCOMPLISHED]->(parent)
                RETURN project,parent,r,collect(child),length(len),currentGoal,accomplish
                ORDER BY r
                CYPHER,
                [
                    'user_email' => $user_email,
                ]
            );

        return $userHasProjetAndHypothesis;
    }

    /**
     * ※絶対にこんな書き方して言い訳がない
     * 会員登録後の使い方のテンプレをKagaction内で表示する
     */
    public function createHowToKagaction(array $uuids, string $user_email)
    {
        $this->client->run(
            <<<'CYPHER'
                    MATCH (user:User{email:$user_email})
                    CREATE (user) - [:HAS{at:localdatetime({timezone: 'Asia/Tokyo'})}] -> (project:Project{name:'Kagactionの使い方', uuid:'$projectUuid'})
                    CREATE (project) <- [:IS_THE_GOAL_OF] - (goal:Hypothesis{name:'Kagactionの使い方を知る', uuid:'$uuid1'}) 
                    CREATE (goal) <- [:TO_ACHEVE] - (hypo1:Hypothesis{name:'1. プロジェクトを作成する', uuid:'$uuid2'}) <- [:CREATED] - (user)
                    CREATE (hypo1) <- [:TO_ACHEVE] - (:Hypothesis{name:'1-1. プロジェクトページに移動する', uuid:'$uuid3'}) <- [:CREATED] - (user)
                    CREATE (hypo1) <- [:TO_ACHEVE] - (:Hypothesis{name:'1-2. プロジェクトを追加するボタンからプロジェクトの名前を作成する', uuid:'$uuid4'})<- [:CREATED] - (user)
                    CREATE (goal) <- [:TO_ACHEVE] - (hypo2:Hypothesis{name:'2. ゴール（目標）を作成する', uuid:'$uuid5'}) <- [:CREATED] - (user)
                    CREATE (hypo2) <- [:TO_ACHEVE] - (:Hypothesis{name:'2-1. 作成したプロジェクトのページに移動する', uuid:'$uuid6'}) <- [:CREATED] - (user)
                    CREATE (hypo2) <- [:TO_ACHEVE] - (:Hypothesis{name:'2-2. ゴールを追加のボタンを押して、ゴール（目標）を入力する', uuid:'$uuid7'}) <- [:CREATED] - (user)
                    CREATE (goal) <- [:TO_ACHEVE] - (hypo3:Hypothesis{name:'3. 課題を作成する', uuid:'$uuid8'}) <- [:CREATED] - (user)
                    CREATE (hypo3) <- [:TO_ACHEVE] - (:Hypothesis{name:'3-1. 作成したゴールのページに移動する', uuid:'$uuid9'}) <- [:CREATED] - (user)
                    CREATE (hypo3) <- [:TO_ACHEVE] - (:Hypothesis{name:'3-2. ゴールを完了するために必要なことを考える', uuid:'$uuid10'}) <- [:CREATED] - (user)
                    CREATE (hypo3) <- [:TO_ACHEVE] - (:Hypothesis{name:'3-3. ゴールを完了するための課題を追加する', uuid:'$uuid11'}) <- [:CREATED] - (user)
                    CREATE (goal) <- [:TO_ACHEVE] - (hypo4:Hypothesis{name:'4. ToDoを設定する', uuid:'$uuid12'}) <- [:CREATED] - (user)
                    CREATE (hypo4) <- [:TO_ACHEVE] - (:Hypothesis{name:'4-1. ToDoに設定したい課題のカードをクリックして詳細ページに移動する', uuid:'$uuid13'}) <- [:CREATED] - (user)
                    CREATE (hypo4) <- [:TO_ACHEVE] - (:Hypothesis{name:'4-2. ToDo項目のチェックボックスをクリックする', uuid:'$uuid14'}) <- [:CREATED] - (user)
                    CREATE (hypo4) <- [:TO_ACHEVE] - (:Hypothesis{name:'4-3. 課題が今すぐToDoできない場合は課題の課題を繰り返し作成する', uuid:'$uuid15'}) <- [:CREATED] - (user)
                    CREATE (goal) <- [:TO_ACHEVE] - (hypo5:Hypothesis{name:'5. ToDoを完了する', uuid:'$uuid16'}) <- [:CREATED] - (user)
                    CREATE (hypo5) <- [:TO_ACHEVE] - (:Hypothesis{name:'5-1. ToDoを実行する', uuid:'$uuid17'}) <- [:CREATED] - (user)
                    CREATE (hypo5) <- [:TO_ACHEVE] - (:Hypothesis{name:'5-2. ToDoページから該当のToDoを探して詳細ページに移動する', uuid:'$uuid18'}) <- [:CREATED] - (user)
                    CREATE (hypo5) <- [:TO_ACHEVE] - (:Hypothesis{name:'5-3. ToDoが完了した場合は完了のチェックボックスをクリック', uuid:'$uuid19'}) <- [:CREATED] - (user)
                    CREATE (hypo5) <- [:TO_ACHEVE] - (:Hypothesis{name:'5-4. ToDoが完了していない場合はToDoの課題を作成', uuid:'$uuid20'}) <- [:CREATED] - (user)
                    CREATE (goal) <- [:TO_ACHEVE] - (hypo6:Hypothesis{name:'6. 削除する', uuid:'$uuid21'}) <- [:CREATED] - (user)
                    CREATE (hypo6) <- [:TO_ACHEVE] - (:Hypothesis{name:'6-1. 削除したいゴールや課題のカードの右側にある3点リーダーをクリックして削除', uuid:'$uuid22'}) <- [:CREATED] - (user)
                CYPHER,
                [
                    'user_email' => $user_email,
                    'projectUuid' => $uuids[0],
                    'uuid1' => $uuids[1],
                    'uuid2' => $uuids[2],
                    'uuid3' => $uuids[3],
                    'uuid4' => $uuids[4],
                    'uuid5' => $uuids[5],
                    'uuid6' => $uuids[6],
                    'uuid7' => $uuids[7],
                    'uuid8' => $uuids[8],
                    'uuid9' => $uuids[9],
                    'uuid10' => $uuids[10],
                    'uuid11' => $uuids[11],
                    'uuid12' => $uuids[12],
                    'uuid13' => $uuids[13],
                    'uuid14' => $uuids[14],
                    'uuid15' => $uuids[15],
                    'uuid16' => $uuids[16],
                    'uuid17' => $uuids[17],
                    'uuid18' => $uuids[18],
                    'uuid19' => $uuids[19],
                    'uuid20' => $uuids[20],
                    'uuid21' => $uuids[21],
                    'uuid22' => $uuids[22],
                ]
            );
    }
}
