<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getPaginatedUsers( Request $request): Paginator;
    // public function getTopSpenders(string $search): Paginator;
    // public function getTopBuyers(string $search): Paginator;
    // public function getInactiveUsers(string $search): Paginator;
    // public function getUserWithStats(int $userId): \App\Models\User;
}