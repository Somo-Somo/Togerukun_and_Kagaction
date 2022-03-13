<?php

namespace App\UseCases\Initialize;

use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\Hypothesis\Converter\HypothesisListConverter;
use App\UseCases\Project\Converter\ProjectListConverter;

class GetUserHasProjectAndHypothesisAction
{
    protected $user_repository;
    protected $hypothesisListConverter;
    protected $projectListConverter;

    public function __construct(UserRepositoryInterface $userRepositoryInterface, HypothesisListConverter $hypothesisListConverter, ProjectListConverter $projectListConverter)
    {
        $this->user_repository = $userRepositoryInterface;
        $this->hypothesisListConverter = $hypothesisListConverter;
        $this->projectListConverter = $projectListConverter;
    }

    public function invoke($userEmail)
    {
        $fetchProjectAndHypothesisFromNeo4j = $this->user_repository->getUserHasProjetAndHypothesis($userEmail);
        $hypothesisList = $this->hypothesisListConverter->invoke($fetchProjectAndHypothesisFromNeo4j);
        return $hypothesisList;
    }
}