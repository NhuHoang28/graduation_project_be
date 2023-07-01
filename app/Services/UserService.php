<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function getMe(int $id)
    {
        return User::select([ 'user_info.*', 'role.*', 'users.*'])
            -> join('user_info', 'user_info.user_id', 'users.id')
            ->join('role', 'users.role_id', 'role.id')
            ->where('users.id', $id)
            ->first();
    }
}
