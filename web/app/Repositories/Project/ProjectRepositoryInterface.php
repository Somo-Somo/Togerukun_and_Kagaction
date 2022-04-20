<?php

namespace App\Repositories\Project;

interface ProjectRepositoryInterface
{
    public function getProjectList($user_id);
    public function create($project);
    public function update(array $project);
    public function destroy(array $project);
    public function generateInitialTemplate(string $user_email);

}