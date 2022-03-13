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

        $this->goal_repository->create($goal);
        
    }
}