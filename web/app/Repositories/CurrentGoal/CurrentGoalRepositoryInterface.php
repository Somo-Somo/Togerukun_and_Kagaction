<?php

namespace App\Repositories\CurrentGoal;

interface CurrentGoalRepositoryInterface
{
    public function updateCurrentGoal(array $hypothesis);
    public function destroyCurrentGoal(array $hypothesis);
}