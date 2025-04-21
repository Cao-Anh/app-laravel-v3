<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function getPaginatedUsers(Request $request): Paginator
    {
        return User::commonSearch($request->search)
        ->withOrderStats()
        ->latest()
        ->paginate(10)
        ->appends($request->query());
    }

    // public function getTopSpenders(string $search): Paginator
    // {
    //     return User::commonSearch($search)
    //         ->withOrderStats()
    //         ->orderByDesc('total_spent')
    //         ->paginate(10);
    // }
    // public function getTopBuyers(string $search): Paginator
    // { 
    //     return User::commonSearch($search)
    //         ->withOrderStats()
    //         ->orderByDesc('total_spent')
    //         ->paginate(10);
    // }
    // public function getInactiveUsers(string $search): Paginator
    // {
    //     return User::commonSearch($search)
    //         ->withOrderStats()
    //         ->orderByDesc('total_spent')
    //         ->paginate(10);
    // }
    // public function getUserWithStats(int $userId): \App\Models\User
    // {

    // }




    // Implement other interface methods similarly
}