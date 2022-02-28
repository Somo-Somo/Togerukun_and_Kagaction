<?php

namespace App\Repositories\Hypothesis;

use Illuminate\Support\Facades\Neo4jDB;
use App\Repositories\Hypothesis\HypothesisRepositoryInterface;

class HypothesisRepository implements HypothesisRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function create($hypothesis)
    {
        return $hypothesis;
    }
}
