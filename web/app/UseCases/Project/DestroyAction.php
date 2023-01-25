<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class DestroyAction
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
     * Repostiroy介してDBからプロジェクトを削除
     *
     * @param array $project
     */
    public function invoke(array $project)
    {
        $this->project_repository->destroy($project);
    }
}
