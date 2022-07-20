<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class StoreAction
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
     * Repository介してプロジェクトをDBに保存
     * プロジェクトだけまだVue内でUuid生成して値を保持するをしてないので
     * Laravel側で値を返している
     * →後でVue側だけで維持するようにしたい。
     * →後にGraphQLとか使いたい
     *
     * @param array $project
     * @return array $formatedProject
     */
    public function invoke(array $project)
    {

        $createdProject = $this->project_repository->create($project);

        //本当はCreatedProjectResource.phpで処理したかったけど出来なくてこちらで
        $formatedProject = $createdProject->getAsCypherMap(0)->getAsNode('project')->getProperties()->toArray();

        // 他にも処理がある場合はここに色々書く
        return $formatedProject;
    }
}
