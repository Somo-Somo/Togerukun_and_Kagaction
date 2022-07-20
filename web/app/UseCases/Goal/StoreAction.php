<?php

namespace App\UseCases\Goal;

use App\Repositories\Goal\GoalRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;

class StoreAction
{
    protected $goal_repository;

    /**
     * @param App\Repositories\Goal\GoalRepositoryInterface $goalRepositoryInterface
     * @param App\Repositories\Date\DateRepositoryInterface $dateRepositoryInterface
     */
    public function __construct(
        GoalRepositoryInterface $goalRepositoryInterface,
        DateRepositoryInterface $dateRepositoryInterface
    ) {
        $this->goal_repository = $goalRepositoryInterface;
        $this->date_repository = $dateRepositoryInterface;
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
