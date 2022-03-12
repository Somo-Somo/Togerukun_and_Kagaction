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

        $fetchProjectAndHypothesisFromNeo4j = $this->hypothesis_repository->create($hypothesis);

        $projectList = $this->projectListConverter->invoke($fetchProjectAndHypothesisFromNeo4j);
        $hypothesisList = $this->hypothesisListConverter->invoke($fetchProjectAndHypothesisFromNeo4j);

        $createdHypothesisIsRelatedProjectAndHypothesis = [
            'project' => $projectList,
            'hypothesis' => $hypothesisList,
        ];
        
        return $createdHypothesisIsRelatedProjectAndHypothesis;
    }
}