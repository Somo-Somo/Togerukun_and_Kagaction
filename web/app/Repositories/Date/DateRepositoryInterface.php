<?php

namespace App\Repositories\Date;

interface DateRepositoryInterface
{
    public function getDate(string $user_uuid);
    public function updateDate(array $todo);
    public function destroyDate(array $todo);
}
