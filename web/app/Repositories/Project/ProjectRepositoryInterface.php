<?php

namespace App\Repositories\Project;

interface ProjectRepositoryInterface
{
    public function getProjectList(int $user_id);
    public function create(array $project);
    public function update(array $project);
    public function destroy(array $project);
    public function generateInitialTemplate(int $user_id);
}
