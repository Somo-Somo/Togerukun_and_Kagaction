<?php

namespace App\UseCases\HypothesisStatus;

use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class UpdateAction
{
    protected $hypothesis_repository;

    public function __construct(HypothesisRepositoryInterface $hypothesisRepositoryInterface)
    {
        $this->hypothesis_repository = $hypothesisRepositoryInterface;
    }

    public function invoke(array $hypothesis)
    {

        $this->hypothesis_repository->updateStatus($hypothesis);
        return; 
    }
}