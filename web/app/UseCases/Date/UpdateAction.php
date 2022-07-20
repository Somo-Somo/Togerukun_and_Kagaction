<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;

class UpdateAction
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
     * Repositoryを介して日付を更新する
     *
     * @param array $todo
     */
    public function invoke(array $todo)
    {
        $this->date_repository->updateDate($todo);
    }
}
