<?php

namespace packages\Domain\User;

interface UserRepositoryInterface
{
    public function register($user);
}