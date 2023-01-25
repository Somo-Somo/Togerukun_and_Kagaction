<?php

namespace App\Repositories\Project;

interface ProjectRepositoryInterface
{
    public function getProjectList(string $user_uuid);
    public function create(array $project);
    public function update(array $project);
    public function destroy(array $project);
    public function generateInitialTemplate(string $user_uuid);
}
