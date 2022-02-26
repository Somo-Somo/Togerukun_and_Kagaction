<?php

namespace App\Repositories\Project;

interface ProjectRepositoryInterface
{
    public function getProjectList($user_id);
    public function create($project);
}