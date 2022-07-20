<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class StoreAction
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
     * Repository介してプロジェクトをDBに保存
     * プロジェクトだけまだVue内でUuid生成して値を保持するをしてないので
     * Laravel側で値を返している
     * →後でVue側だけで維持するようにしたい。
     * →後にGraphQLとか使いたい
     *
     * @param array $project
     * @return array $formated_project
     */
    public function invoke(array $project)
    {

        $created_project = $this->project_repository->create($project);

        //本当はCreatedProjectResource.phpで処理したかったけど出来なくてこちらで
        $formated_project = $created_project->getAsCypherMap(0)->getAsNode('project')->getProperties()->toArray();

        // 他にも処理がある場合はここに色々書く
        return $formated_project;
    }
}
