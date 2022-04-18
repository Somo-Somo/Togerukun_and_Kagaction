<?php

namespace App\UseCases\HowToKagaction;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Str;

class StoreAction
{
    protected $user_repository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->user_repository = $userRepositoryInterface;
    }

    public function invoke(string $user_email)
    {
        $uuids = [];
        for ($i=0; $i < 23; $i++) { 
            array_push($uuids,(string) Str::uuid());
        }

        $this->user_repository->createHowToKagaction($uuids, $user_email);

        return;
    }
}