<?php

namespace App\Repositories\Date;

interface DateRepositoryInterface
{
    public function updateDate(array $hypothesis);
    public function destroyDate(array $hypothesis);
}