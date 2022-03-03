<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class DestroyAction
{
    protected $project_repository;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    public function invoke(array $project)
    {
        $deletedDataFromDB = $this->project_repository->destroy($project);
        
        return $deletedDataFromDB;
    }
}