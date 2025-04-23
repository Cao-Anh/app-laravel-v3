<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Order;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        // private UserService $userService
    ) {}

    public function index(Request $request)
    {
        $users = $this->userRepository->getPaginatedUsers($request);

        logActivity('View Users');

        return view('users.index', compact('users'));
    }


    public function show($user)
    {
        $user = User::withOrderStats()->findOrFail($user);
        logActivity('View User', "Viewed user with ID {$user->id}");

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

        $this->userRepository->store($request);
        logActivity('Create User', "Created a user");
        return redirect()->route('users.index')->with('success', 'Tạo người dùng thành công.');
    }



    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        logActivity('Edit User', "Access edit user with id: {$user->id}");
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request,User $user)
    {
        Gate::authorize('update', $user);
        $this->userRepository->update($request,$user);
        
        logActivity('Edit User', "Edited user with ID {$user->id}");
        return redirect()->route('users.show', $user->id)->with('success', 'Cập nhật thành công.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        logActivity('Delete User', "Delete user with ID {$user->id}");
        return redirect()->route('users.index')->with('success', 'Xóa thành công.');
    }

    public function getTopBuyTimeUsers(Request $request)
    {
        
        $users = $this->userRepository->getTopBuyers($request);

        logActivity('View top buy time users');
        return view('users.top_buy_time', compact('users'));
    }

    /**
     * 
     */
    public function getTopSpendUsers(Request $request)
    {
        $users = $this->userRepository->getTopSpenders($request);

        logActivity('View top spend users');
        return view('users.top_spend', compact('users'));
    }

    public function getNoOrderUsers(Request $request)
    {
        $users = $this->userRepository->getInactiveUsers($request);

        logActivity('View no order users');

        return view('users.no_orders', compact('users'));
    }

    public function sortByNameAsc(Request $request)
    {
        $users = $this->userRepository->sortByNameAsc($request);


        logActivity('sort users by name asc');
        return view('users.index', compact('users'));
    }

    public function sortByNameDesc(Request $request)
    {
        $users = $this->userRepository->sortByNameDesc($request);


        return view('users.index', compact('users'));
    }

    public function getPurchaseHistory(User $user)
    {      
        $orders = $this->userRepository->getUserWithStats($user);

        logActivity('View purchase history', "View purchase history of user with id {$user->id}");

        return view('users.purchase_history', compact('user', 'orders'));
    }
}
