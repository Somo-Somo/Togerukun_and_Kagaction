<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function register($user);
    public function getUserHasProjetAndHypothesis(string $user_email);
}