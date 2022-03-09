<?php

namespace App\Repositories\TodaysGoal;

interface TodaysGoalRepositoryInterface
{
    public function updateTodaysGoal(array $hypothesis);
    public function destroyTodaysGoal(array $hypothesis);
}