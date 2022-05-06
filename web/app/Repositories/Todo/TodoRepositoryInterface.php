<?php

namespace App\Repositories\Todo;

interface TodoRepositoryInterface
{
    public function getTodoList(string $projectUuid);
    public function create($todo);
    public function update(array $todo);
    public function destroy(array $todo);
    public function updateAccomplish(array $todo);
    public function destroyAccomplish(array $todo);
}