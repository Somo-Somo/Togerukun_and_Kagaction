<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class UpdateAction
{
    protected $project_repository;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    public function invoke(array $project)
    {

        $updatedProject = $this->project_repository->update($project);

        $formatedProject = $updatedProject->getAsCypherMap(0)->getAsNode('project')->getProperties()->toArray();
        
        return $formatedProject;
    }
}