<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;

class DestroyAction
{
    protected $date_repository;

    public function __construct(DateRepositoryInterface $dateRepositoryInterface)
    {
        $this->date_repository = $dateRepositoryInterface;
    }

    public function invoke(array $todo)
    {

        $this->date_repository->destroyDate($todo);
        
        return; 
    }
}