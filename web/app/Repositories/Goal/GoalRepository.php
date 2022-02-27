<?php

namespace App\Repositories\Goal;

use Illuminate\Support\Facades\Neo4jDB;
use App\Repositories\Goal\GoalRepositoryInterface;

class GoalRepository implements GoalRepositoryInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = Neo4jDB::call();
    }

    public function create($goal)
    {
        return $goal;
    }
}
