<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function register($user);
    public function getUserHasProjetAndTodo(string $user_email);
}