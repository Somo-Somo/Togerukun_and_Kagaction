<?php

namespace App\UseCases\Hypthesis;

use App\Repositories\Hypthesis\HypthesisRepositoryInterface;

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

        // $cypherMapGoal = $saveGoalToDB->getAsCypherMap(0);

        // $createdGoal = [
        //     'parent' => $cypherMapGoal->getAsNode('project')->getProperties()->toArray(),
        //     'hypothesis' => $cypherMapGoal->getAsNode('hypothesis')->getProperties()->toArray(),
        // ];
        
        return $saveHypothesisToDB;
    }
}