<?php

namespace App\Repositories\Cause;

interface CauseRepositoryInterface
{
    public function storeCause(array $cause);
    public function destroyCause(array $cause);
}
