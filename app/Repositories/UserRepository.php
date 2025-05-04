<?php

namespace App\Repositories;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
// use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Elastic\Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{

    // public function getPaginatedUsers(Request $request): Paginator
    // {
    //     return User::commonSearch($request->search)
    //         ->withOrderStats()
    //         ->latest()
    //         ->paginate(10)
    //         ->appends($request->query());
    // }


    public function __construct(
        protected Client $es,
    ) {}

    
    /**
     * Get paginated users, optionally full-text searching username & email via Elasticsearch.
     */
    public function getPaginatedUsers(Request $request): LengthAwarePaginator
    {
        $search  = (string) $request->input('search', '');
        $page    = (int) $request->input('page', 1);
        $perPage = 10;

        // If no search term, fall back to plain Eloquent (withOrderStats + paginate)
        if ($search === '') {
            return User::withOrderStats()
                       ->latest()
                       ->paginate($perPage)
                       ->appends($request->query());
        }

        $from = ($page - 1) * $perPage;

        // 1) Run the ES query against username & email
        $resp = $this->es->search([
            'index' => 'users',
            'body'  => [
                'from'             => $from,
                'size'             => $perPage,
                'track_total_hits' => true,    // get the real total
                'query' => [
                    'multi_match' => [
                        'query'  => $search,
                        'fields' => ['username^2', 'email'],   // boost username if desired
                        'type'   => 'best_fields',
                    ],
                ],
                'sort' => [
                    ['created_at' => ['order' => 'desc']],
                ],
            ],
        ]);

        // 2) Extract IDs & total hit count
        $hits  = $resp['hits']['hits'] ?? [];
        $ids   = array_map(fn($h) => (int) $h['_id'], $hits);
        $total = $resp['hits']['total']['value'] ?? 0;

        if (empty($ids)) {
            // No hits: return an empty paginator
            return new LengthAwarePaginator(
                collect(),
                0,
                $perPage,
                $page,
                [
                    'path'  => Paginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ]
            );
        }

        // 3) Hydrate Eloquent models (with your order-stats) and preserve ES order
        $users = User::whereIn('id', $ids)
                     ->withOrderStats()
                     ->get()
                     ->sortBy(fn($u) => array_search($u->id, $ids))
                     ->values();

        // 4) Return LengthAwarePaginator so ->links() etc. work
        return new LengthAwarePaginator(
            $users,
            $total,
            $perPage,
            $page,
            [
                'path'  => Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );
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



    public function getTopBuyers(Request $request): LengthAwarePaginator
    {
        return  User::commonSearch($request->search)
            ->withOrderQuantityStats()
            ->orderByDesc('total_quantity')
            ->paginate(10)
            ->appends($request->query());
    }
    public function getTopSpenders(Request $request): LengthAwarePaginator
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
