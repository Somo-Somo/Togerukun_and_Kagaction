<?php

namespace App\UseCases\Project;

use App\Repositories\Project\ProjectRepositoryInterface;

class IndexAction
{
    protected $project_repository;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    public function invoke($user_email)
    {
        $projectCypherMap = $this->project_repository->getProjectList($user_email);

        $projcetsCypherList = $projectCypherMap->toArray();
        $projectList = [];
        foreach ($projcetsCypherList as $project) {
            $projectList[] = $project->getAsNode('project')->getProperties()->toArray();
        }
        
        // 他にも処理がある場合はここに色々書く
        return $projectList;
    }
}