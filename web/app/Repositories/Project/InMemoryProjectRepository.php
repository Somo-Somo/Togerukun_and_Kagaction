<?php

namespace App\Repositories\Project;

class InMemoryProjectRepository
{
    private $data;

    public function __construct()
    {
        $this->data = collect();
    }

    public function create($project)
    {
        $this->data->push($project);
    }

    public function first()
    {
        return $this->data->first();
    }
}