<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm(){
        return view('auth.register');
    }
    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();
        
        $user=User::create([
            'username'=>$validated['username'],
            'email'=>$validated['email'],
            'password'=>Hash::make($validated['password']),
        ]);
        Auth::login($user);
        return redirect()->route('users.index')->with('success','Tạo tài khoản thành công.');
             
    }
}
