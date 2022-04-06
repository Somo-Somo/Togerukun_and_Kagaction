<?php

namespace App\UseCases\Accomplish;

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

        $this->hypothesis_repository->updateAccomplish($hypothesis);
        return; 
    }
}