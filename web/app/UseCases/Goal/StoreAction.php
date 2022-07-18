<?php

namespace App\UseCases\Goal;

use App\Repositories\Goal\GoalRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;

class StoreAction
{
    protected $goal_repository;

    public function __construct(
        GoalRepositoryInterface $goalRepositoryInterface,
        DateRepositoryInterface $dateRepositoryInterface
    ) {
        $this->goal_repository = $goalRepositoryInterface;
        $this->date_repository = $dateRepositoryInterface;
    }

    public function invoke(array $goal)
    {
        $this->goal_repository->create($goal);

        if ($goal['date']) {
            $this->date_repository->updateDate($goal);
        }

        return;
    }
}
