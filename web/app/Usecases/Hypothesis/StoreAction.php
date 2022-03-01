<?php

namespace App\UseCases\Hypothesis;

use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class StoreAction
{
    protected $hypothesis_repository;

    public function __construct(HypothesisRepositoryInterface $hypothesisRepositoryInterface)
    {
        $this->hypothesis_repository = $hypothesisRepositoryInterface;
    }

    public function invoke(array $hypothesis)
    {

        $saveHypothesisToDB = $this->hypothesis_repository->create($hypothesis);

        $cypherMapHypothesis = $saveHypothesisToDB->getAsCypherMap(0);

        $createdHypothesis = [
            'parent' => $cypherMapHypothesis->getAsNode('parent')->getProperties()->toArray(),
            'hypothesis' => $cypherMapHypothesis->getAsNode('hypothesis')->getProperties()->toArray(),
        ];
        
        return $createdHypothesis;
    }
}