<?php

namespace App\Interfaces;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    // public function getPaginatedUsers( Request $request): Paginator;

    public function getPaginatedUsers(Request $request): LengthAwarePaginator;
    public function store (StoreUserRequest $request ):void;
    public function update (UpdateUserRequest $request,  User $user ):void;
    public function getTopBuyers(Request $request): LengthAwarePaginator;
    public function getTopSpenders(Request $request): LengthAwarePaginator;
    public function getInactiveUsers(Request $request): Paginator;
    public function sortByNameAsc(Request $request): Paginator;
    public function sortByNameDesc(Request $request): Paginator;
    public function getUserWithStats( User $user):Collection;

}