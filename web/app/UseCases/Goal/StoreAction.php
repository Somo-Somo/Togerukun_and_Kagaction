<?php

namespace App\UseCases\Goal;

use App\Repositories\Goal\GoalRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;

class StoreAction
{
    protected $goal_repository;

    /**
     * @param App\Repositories\Goal\GoalRepositoryInterface $goal_repository_interface
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     */
    public function __construct(
        GoalRepositoryInterface $goal_repository_interface,
        DateRepositoryInterface $date_repository_interface
    ) {
        $this->goal_repository = $goal_repository_interface;
        $this->date_repository = $date_repository_interface;
    }

    /**
     * ゴールをRepository介してDBに保存
     * ゴールに日付がある場合はRepositoryを介して日付を更新する
     *
     * @param array $goal
     */
    public function invoke(array $goal)
    {
        $this->goal_repository->create($goal);

        if ($goal['date']) {
            $this->date_repository->updateDate($goal);
        }
    }
}
