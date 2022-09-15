<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function register(object $user);
    public function whetherExecuteOnboarding(int $user_id);
    public function finishedOnboarding(array $onboarding);
    public function getUserHasProjetAndTodo(int $user_id);
}
