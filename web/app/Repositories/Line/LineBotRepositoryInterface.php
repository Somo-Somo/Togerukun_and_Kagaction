<?php

namespace App\Repositories\Line;

interface LineBotRepositoryInterface
{
    public function findIfProjectExists(string $id);
}
