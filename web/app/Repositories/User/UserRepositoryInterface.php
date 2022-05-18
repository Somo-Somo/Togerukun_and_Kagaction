<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function register($user);
    public function whetherExecuteOnboarding(string $user_email);
    public function finishedOnboarding(array $onboarding);
    public function getUserHasProjetAndTodo(string $user_email);
}