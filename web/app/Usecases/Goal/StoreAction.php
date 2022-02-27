<?php

namespace App\UseCases\Goal;

use App\Repositories\Goal\GoalRepositoryInterface;

class StoreAction
{
    protected $goal_repository;

    public function __construct(GoalRepositoryInterface $goalRepositoryInterface)
    {
        $this->goal_repository = $goalRepositoryInterface;
    }

    public function invoke(array $goal)
    {

        $createdGoal = $this->goal_repository->create($goal);

        // 本当はCreatedProjectResource.phpで処理したかったけど出来なくてこちらで
        // $formatedProject = $createdProject->getAsCypherMap(0)->getAsNode('project')->getProperties()->toArray();
        
        return $createdGoal;
    }
}