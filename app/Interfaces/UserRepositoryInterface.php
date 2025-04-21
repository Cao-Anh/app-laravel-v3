<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getPaginatedUsers( Request $request): Paginator;
    public function getTopSpenders(Request $request): Paginator;
    public function getTopBuyers(Request $request): Paginator;
    public function getInactiveUsers(Request $request): Paginator;
    public function sortByNameAsc(Request $request): Paginator;
    public function sortByNameDesc(Request $request): Paginator;
    public function getUserWithStats(int $id, User $user):Collection;
}