<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class UpdateAction
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
     * Repositoryを介してプロジェクトの名前を更新する
     *
     * @param array $project
     */
    public function invoke(array $project)
    {
        $this->project_repository->update($project);
    }
}
