<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function register(object $user);
    public function whetherExecuteOnboarding(string $user_uuid);
    public function finishedOnboarding(array $onboarding);
    public function getUserHasProjetAndTodo(string $user_uuid);
}
