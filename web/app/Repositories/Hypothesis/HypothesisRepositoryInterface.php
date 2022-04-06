<?php

namespace App\Repositories\Hypothesis;

interface HypothesisRepositoryInterface
{
    public function getHypothesisList(string $projectUuid);
    public function create($hypothesis);
    public function update(array $hypothesis);
    public function destroy(array $hypothesis);
    public function updateAccomplish(array $hypothesis);
    public function destroyAccomplish(array $hypothesis);
}