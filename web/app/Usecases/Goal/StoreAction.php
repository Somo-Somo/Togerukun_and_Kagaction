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

        $saveGoalToDB = $this->goal_repository->create($goal);

        $cypherMapGoal = $saveGoalToDB->getAsCypherMap(0);

        $createdGoal = [
            'parent' => $cypherMapGoal->getAsNode('project')->getProperties()->toArray(),
            'hypothesis' => $cypherMapGoal->getAsNode('hypothesis')->getProperties()->toArray(),
        ];
        
        return $createdGoal;
    }
}