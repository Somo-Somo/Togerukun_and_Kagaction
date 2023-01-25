<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;

class DestroyAction
{
    protected $date_repository;

    /**
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     */
    public function __construct(DateRepositoryInterface $date_repository_interface)
    {
        $this->date_repository = $date_repository_interface;
    }

    /**
     * Repository介してDB上の日付を削除する
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->date_repository->destroyDate($todo);
    }
}
