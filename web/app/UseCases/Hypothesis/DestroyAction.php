<?php

namespace App\UseCases\Hypothesis;

use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class DestroyAction
{
    protected $hypothesis_repository;

    public function __construct(HypothesisRepositoryInterface $hypothesisRepositoryInterface)
    {
        $this->hypothesis_repository = $hypothesisRepositoryInterface;
    }

    public function invoke(array $hypothesis)
    {
        $deletedDataFromDB = $this->hypothesis_repository->destroy($hypothesis);
        
        return $deletedDataFromDB;
    }
}