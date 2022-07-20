<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class DestroyAction
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
     * Repostiroy介してDBからプロジェクトを削除
     *
     * @param array $project
     */
    public function invoke(array $project)
    {
        $this->project_repository->destroy($project);
    }
}
