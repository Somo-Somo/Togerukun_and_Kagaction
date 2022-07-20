<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class IndexAction
{
    protected $project_repository;

    /**
     * @param App\Repositories\Project\ProjectRepositoryInterface $projectRepositoryInterface
     */
    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    /**
     * ユーザーが保持しているプロジェクトをDBから取得
     * 配列化して、uuidをKEYに連想配列で一覧を作る
     *
     * @param string $user_email
     * @return array $projectList
     */
    public function invoke(string $user_email)
    {
        $projectCypherMap = $this->project_repository->getProjectList($user_email);

        $projcetsCypherList = $projectCypherMap->toArray();
        $projectList = [];
        foreach ($projcetsCypherList as $projectData) {
            $project = $projectData->getAsNode('project')->getProperties()->toArray();
            $projectList[$project['uuid']] = $project;
        }

        // 他にも処理がある場合はここに色々書く
        return $projectList;
    }
}
