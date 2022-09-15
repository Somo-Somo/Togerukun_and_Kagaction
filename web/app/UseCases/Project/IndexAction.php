<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class IndexAction
{
    protected $project_repository;

    /**
     * @param App\Repositories\Project\ProjectRepositoryInterface $project_repository_interface
     */
    public function __construct(ProjectRepositoryInterface $project_repository_interface)
    {
        $this->project_repository = $project_repository_interface;
    }

    /**
     * ユーザーが保持しているプロジェクトをDBから取得
     * 配列化して、uuidをKEYに連想配列で一覧を作る
     *
     * @param int $user_id
     * @return array $project_list
     */
    public function invoke(int $user_id)
    {
        $project_cypher_map = $this->project_repository->getProjectList($user_id);

        $projcets_cypher_list = $project_cypher_map->toArray();
        $project_list = [];
        foreach ($projcets_cypher_list as $project_data) {
            $project = $project_data->getAsNode('project')->getProperties()->toArray();
            $project_list[$project['uuid']] = $project;
        }

        // 他にも処理がある場合はここに色々書く
        return $project_list;
    }
}
