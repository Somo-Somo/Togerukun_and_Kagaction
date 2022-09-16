<?php

namespace App\Repositories\Date;

interface DateRepositoryInterface
{
    public function getDate(int $user_id);
    public function updateDate(array $todo);
    public function destroyDate(array $todo);
}
