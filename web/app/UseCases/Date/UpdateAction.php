<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;

class UpdateAction
{
    protected $date_repository;

    public function __construct(DateRepositoryInterface $dateRepositoryInterface)
    {
        $this->date_repository = $dateRepositoryInterface;
    }

    public function invoke(array $hypothesis)
    {

        $this->date_repository->updateDate($hypothesis);
        
        return; 
    }
}