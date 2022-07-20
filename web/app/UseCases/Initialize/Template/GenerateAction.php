<?php

namespace App\UseCases\Initialize\Template;

use App\Repositories\Project\ProjectRepositoryInterface;

class GenerateAction
{
    protected $project_repository;

    /**
     * @param App\Repositories\Project\ProjectRepositoryInterface $projectRepositoryInterface
     */
    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->project_repository = $projectRepositoryInterface;
    }

    /**
     * テンプレートのプロジェクトをRepository介してDBに保存
     *
     * @param string $user_email
     */
    public function invoke(string $user_email)
    {
        $this->project_repository->generateInitialTemplate($user_email);
    }
}
