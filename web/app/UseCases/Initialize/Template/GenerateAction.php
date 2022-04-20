<?php

namespace App\UseCases\Initialize\Template;

use App\Repositories\Project\ProjectRepositoryInterface;

class GenerateAction
{
    protected $project_repository;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    public function invoke($user_email)
    {
        $this->project_repository->generateInitialTemplate($user_email);
        return;
    }
}