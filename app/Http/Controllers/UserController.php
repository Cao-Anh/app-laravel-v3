<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Order;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::commonSearch($search)
            ->withOrderStats()
            ->latest()
            ->paginate(10)
            ->appends($request->query());
        logActivity('View Users');

        return view('users.index', compact('users'));
    }


    public function show($id)
    {
        $user = User::withOrderStats()->findOrFail($id);
        logActivity('View User', "Viewed user with ID {$id}");
        return view('users.show', compact('user'));
    }

    public function create()
    {
        Gate::authorize('create', User::class);
        $roles = Role::all();
        logActivity('Create User', "Access create user");
        return view('users.create', compact('roles'));
    }


    public function store(StoreUserRequest $request)
    {
        Gate::authorize('create', User::class);

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

        logActivity('Create User', "Created a user");
        return redirect()->route('users.index')->with('success', 'Tạo người dùng thành công.');
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);
        Gate::authorize('update', $user);
        logActivity('Edit User', "Access edit user with id: {$id}");
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {

        $user = User::findOrFail($id);
        Gate::authorize('update', $user);

        $validated = $request->validated();

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        $user->save();
        logActivity('Edit User', "Edited user with ID {$id}");
        return redirect()->route('users.show', $id)->with('success', 'Cập nhật thành công.');
    }


    public function destroy(Request $request, $id)
    {

        $user = User::findOrFail($id);
        Gate::authorize('delete', $user);
        $user->delete();
        logActivity('Delete User', "Delete user with ID {$id}");
        return redirect()->route('users.index')->with('success', 'Xóa thành công.');
    }

    public function getTopBuyTimeUsers(Request $request)
    {
        $search = $request->input('search');

        $users = User::commonSearch($search)
            ->withOrderQuantityStats()
            ->orderByDesc('total_quantity')
            ->paginate(10)
            ->appends($request->query());

        logActivity('View top buy time users');
        return view('users.top_buy_time', compact('users'));
    }

    public function getTopSpendUsers(Request $request)
    {
        $search = $request->input('search');

        $users = User::commonSearch($search)
            ->withOrderStats()
            ->orderByDesc('total_spent')
            ->paginate(10)
            ->appends($request->query());

        logActivity('View top spend users');
        return view('users.top_spend', compact('users'));
    }

    public function getNoOrderUsers(Request $request)
    {
        $search = $request->input('search');

        $users = User::commonSearch($search)
            ->doesntHave('orders')
            ->paginate(10)
            ->appends($request->query());
        logActivity('View no order users');

        return view('users.no_orders', compact('users'));
    }

    public function sortByNameAsc(Request $request)
    {
        $search = $request->input('search');

        $users = User::commonSearch($search)
            ->withOrderStats()
            ->sortByName('asc')
            ->paginate(10)
            ->appends($request->query());

        logActivity('sort users by name asc');
        return view('users.index', compact('users'));
    }

    public function sortByNameDesc(Request $request)
    {
        $search = $request->input('search');

        $users = User::commonSearch($search)
            ->withOrderStats()
            ->sortByName('desc')
            ->paginate(10)
            ->appends($request->query());

        logActivity('sort users by name desc');

        return view('users.index', compact('users'));
    }

    public function getPurchaseHistory($id)
    {
        $user = User::findOrFail($id);
        $orders = $user->orders()
            ->with(['orderDetails.product'])
            ->orderByDesc('created_at')
            ->get();
        logActivity('View purchase history', "View purchase history of user with id {$id}");

        return view('users.purchase_history', compact('user', 'orders'));
    }
}
