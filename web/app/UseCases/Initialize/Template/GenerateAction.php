<?php

namespace App\UseCases\Initialize\Template;

use App\Repositories\Project\ProjectRepositoryInterface;

class GenerateAction
{
    protected $project_repository;

    /**
     * @param App\Repositories\Project\ProjectRepositoryInterface $project_repository_interface
     */
    public function __construct(ProjectRepositoryInterface $project_repository_interface)
    {
        $this->project_repository = $project_repository_interface;
    }

    /**
     * テンプレートのプロジェクトをRepository介してDBに保存
     *
     * @param string $user_uuid
     */
    public function invoke(string $user_uuid)
    {
        $this->project_repository->generateInitialTemplate($user_uuid);
    }
}
