<?php

namespace App\Contracts\Services;

interface AppUserService
{
    public function storeUserProfile(int $userId);

    public function deleteUserProfile(int $userId);
}
