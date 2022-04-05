<?php

namespace App\UseCases\CurrentGoal;

use App\Repositories\CurrentGoal\CurrentGoalRepositoryInterface;

class DestroyAction
{
    protected $current_goal_repository;

    public function __construct(CurrentGoalRepositoryInterface $currentGoalRepositoryInterface)
    {
        $this->current_goal_repository = $currentGoalRepositoryInterface;
    }

    public function invoke(array $hypothesis)
    {

        $this->current_goal_repository->destroyCurrentGoal($hypothesis);
        
        return; 
    }
}