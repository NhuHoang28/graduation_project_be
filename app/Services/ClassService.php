<?php

namespace App\Services;

use App\Models\User;
use Arr;

class ClassService
{

    public function getListClass($params, $user_id)
    {
        $limit = Arr::get($params, 'limit', config('pagination.limit'));
        $sort = Arr::get($params, 'sort', config('pagination.sort'));
        $order = Arr::get($params, 'order', config('pagination.order'));
        return User::join('class', 'users.id', 'class.user_id')
            ->where('users.id', $user_id)
            ->orderBy($sort, $order)
            ->paginate($limit);
    }
}
