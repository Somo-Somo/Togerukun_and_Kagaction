<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;

class DestroyAction
{
    protected $date_repository;

    /**
     * @param App\Repositories\Date\DateRepositoryInterface $dateRepositoryInterface
     */
    public function __construct(DateRepositoryInterface $dateRepositoryInterface)
    {
        $this->date_repository = $dateRepositoryInterface;
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
