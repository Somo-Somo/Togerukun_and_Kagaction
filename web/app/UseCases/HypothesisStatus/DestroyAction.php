<?php

namespace App\UseCases\HypothesisStatus;

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
        $this->hypothesis_repository->destroyStatus($hypothesis);
        return; 
    }
}