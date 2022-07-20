<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;

class UpdateAction
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
     * Repositoryを介して日付を更新する
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->date_repository->updateDate($todo);
    }
}
