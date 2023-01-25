<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class UpdateAction
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
     * Repositoryを介してプロジェクトの名前を更新する
     *
     * @param array $project
     */
    public function invoke(array $project)
    {
        $this->project_repository->update($project);
    }
}
