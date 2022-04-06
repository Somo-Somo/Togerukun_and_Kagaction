<?php

namespace App\UseCases\Hypothesis;

use App\Repositories\Hypothesis\HypothesisRepositoryInterface;
use App\UseCases\Hypothesis\Converter\HypothesisListConverter;
use App\UseCases\Project\Converter\ProjectListConverter;

class StoreAction
{
    protected $hypothesis_repository;
    protected $hypothesisListConverter;
    protected $projectListConverter;

    public function __construct(HypothesisRepositoryInterface $hypothesisRepositoryInterface, HypothesisListConverter $hypothesisListConverter, ProjectListConverter $projectListConverter)
    {
        $this->hypothesis_repository = $hypothesisRepositoryInterface;
        $this->hypothesisListConverter = $hypothesisListConverter;
        $this->projectListConverter = $projectListConverter;
    }

    public function invoke(array $hypothesis)
    {

        $this->hypothesis_repository->create($hypothesis);
        
        return;
    }
}