<?php

namespace App\Repository;

use App\Enum\UserRole;
use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        $user = new User();
        $user->fill($data);
        $user->save();

        return $user;
    }
    public function createAdmin(array $data)
    {
        $user = new User();
        $user->fill($data);
        $user->role = UserRole::ADMIN;
        $user->save();

        return $user;
    }
}
