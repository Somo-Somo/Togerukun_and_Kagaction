<?php

namespace App\UseCases\TodaysGoal;

use App\Repositories\TodaysGoal\TodaysGoalRepositoryInterface;

class UpdateAction
{
    protected $todays_goal_repository;

    public function __construct(TodaysGoalRepositoryInterface $todaysGoalRepositoryInterface)
    {
        $this->todays_goal_repository = $todaysGoalRepositoryInterface;
    }

    public function invoke(array $hypothesis)
    {

        $this->todays_goal_repository->updateTodaysGoal($hypothesis);
        
        return; 
    }
}