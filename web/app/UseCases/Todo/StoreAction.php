<?php

namespace App\UseCases\Todo;

use App\Repositories\Todo\TodoRepositoryInterface;
use App\Repositories\Date\DateRepositoryInterface;

class StoreAction
{
    protected $todo_repository;
    protected $date_repository;

    /**
     * @param App\Repositories\Todo\TodoRepositoryInterface $todo_repository_interface
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     */
    public function __construct(
        TodoRepositoryInterface $todo_repository_interface,
        DateRepositoryInterface $date_repository_interface
    ) {
        $this->todo_repository = $todo_repository_interface;
        $this->date_repository = $date_repository_interface;
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
