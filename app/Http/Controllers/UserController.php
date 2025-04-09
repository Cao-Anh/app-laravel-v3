<?php

namespace App\Http\Controllers;

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
