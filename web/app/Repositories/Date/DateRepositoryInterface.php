<?php

namespace App\Repositories\Date;

interface DateRepositoryInterface
{
    public function getDate(string $user_email);
    public function updateDate(array $hypothesis);
    public function destroyDate(array $hypothesis);
}