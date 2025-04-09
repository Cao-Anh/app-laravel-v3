<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:8|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'array|exists:roles,id'
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        // Attach roles
        if ($request->has('roles')) {
            $user->roles()->attach($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'Tạo người dùng thành công.');
    }



    public function edit($id)
    {

        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:8',
            'email' => 'required|email|unique:users,email,' . $id,

        ]);

        $user = User::findOrFail($id);

        // if ($request->hasFile('photo')) {
        //     // dd($request->file('photo'));
        //     $file = $request->file('photo');

        //     $imageName = time().'.'.$request->photo->extension();  

        //     $request->photo->move(public_path('images'), $imageName);
        //     $photoUrl= 'images/'. $imageName;

        //     $user->photo = $photoUrl;
        // }



        $user->update([
            'username' => $request->username,
            'email' => $request->email,
        ]);

        $user->save();

        return redirect()->route('users.show', $id)->with('success', 'Cập nhật thành công.');
    }


    public function destroy(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Xóa thành công.');
    }
}
