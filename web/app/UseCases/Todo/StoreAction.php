<?php

namespace App\UseCases\Todo;

use App\Repositories\Todo\TodoRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;

class StoreAction
{
    protected $todo_repository;
    protected $date_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface $todoRepositoryInterface
     * @param App\Repositories\Date\DateRepositoryInterface $dateRepositoryInterface
     */
    public function __construct(
        TodoRepositoryInterface $todoRepositoryInterface,
        DateRepositoryInterface $dateRepositoryInterface
    ) {
        $this->todo_repository = $todoRepositoryInterface;
        $this->date_repository = $dateRepositoryInterface;
    }

    /**
     * Repository介してDBにTodoを保存
     * 日付がついている場合は日付をRepository介してDBに保存
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->todo_repository->create($todo);

        if ($todo['date']) {
            $this->date_repository->updateDate($todo);
        }
    }
}
