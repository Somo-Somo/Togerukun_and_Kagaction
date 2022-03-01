<?php

namespace App\Repositories\Hypothesis;

interface HypothesisRepositoryInterface
{
    public function getHypothesisList(string $projectUuid);
    public function create($hypothesis);
}