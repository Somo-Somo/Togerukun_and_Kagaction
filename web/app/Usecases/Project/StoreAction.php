<?php

namespace App\UseCases\Project;

use App\Repositories\User\ProjectRepositoryInterface;

class StoreAction
{
    protected $project_repository;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    public function invoke(array $project)
    {

        $created = $this->project_repository->created($project);
        
        // 他にも処理がある場合はここに色々書く
        return $created;
    }
}