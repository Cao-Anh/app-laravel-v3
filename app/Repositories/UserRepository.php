<?php

namespace App\Repositories;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
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

    public function store(StoreUserRequest $request): void
    {
        $validated = $request->validated();

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Attach roles
        if (isset($validated['roles'])) {
            $user->roles()->attach($validated['roles']);
        }
    }

    public function update(UpdateUserRequest $request, User $user): void
    {
        $validated = $request->validated();

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        $user->save();
    }



    public function getTopBuyers(Request $request): Paginator
    {
        return  User::commonSearch($request->search)
            ->withOrderQuantityStats()
            ->orderByDesc('total_quantity')
            ->paginate(10)
            ->appends($request->query());
    }
    public function getTopSpenders(Request $request): Paginator
    {
        return User::commonSearch($request->search)
            ->withOrderStats()
            ->orderByDesc('total_spent')
            ->paginate(10)
            ->appends($request->query());
    }
    public function getInactiveUsers(Request $request): Paginator
    {
        return User::commonSearch($request->search)
            ->doesntHave('orders')
            ->paginate(10)
            ->appends($request->query());
    }

    public function sortByNameAsc(Request $request): Paginator
    {
        return User::commonSearch($request->search)
            ->withOrderStats()
            ->sortByName('asc')
            ->paginate(10)
            ->appends($request->query());
    }

    public function sortByNameDesc(Request $request): Paginator
    {
        return User::commonSearch($request->search)
            ->withOrderStats()
            ->sortByName('desc')
            ->paginate(10)
            ->appends($request->query());
    }
    
    public function getUserWithStats(User $user): Collection
    {
        return $user->orders()
            ->with(['orderDetails.product'])
            ->orderByDesc('created_at')
            ->get();
    }




    // Implement other interface methods similarly
}
