<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class StoreAction
{
    protected $project_repository;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    public function invoke(array $project)
    {

        $createdProject = $this->project_repository->create($project);

        //本当はCreatedProjectResource.phpで処理したかったけど出来なくてこちらで
        $formatedProject = $createdProject->getAsCypherMap(0)->getAsNode('project')->getProperties()->toArray();
        
        // 他にも処理がある場合はここに色々書く
        return $formatedProject;
    }
}