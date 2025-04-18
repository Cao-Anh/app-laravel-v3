<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ChangePwController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserActivityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/change-password', [ChangePwController::class, 'showChangePasswordForm'])->name('changePassword');
Route::post('/change-password', [ChangePwController::class, 'changePassword']);

//reset password
{
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware('guest')->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    })->middleware('guest')->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->middleware('guest')->name('password.reset');

    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:5|max:9|regex:/[A-Z]/|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->middleware('guest')->name('password.update');
}

Route::middleware('auth')->group(function () {
    // user routes
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('users/top-buy-time', [UserController::class, 'getTopBuyTimeUsers'])->name('users.top_buy_time');
    Route::get('users/top-spend', [UserController::class, 'getTopSpendUsers'])->name('users.top_spend');
    Route::get('users/no-orders', [UserController::class, 'getNoOrderUsers'])->name('users.no_orders');
    Route::get('users/name-order-asc', [UserController::class, 'sortByNameAsc'])->name('users.name_order_asc');
    Route::get('users/name-order-desc', [UserController::class, 'sortByNameDesc'])->name('users.name_order_desc');
    Route::get('/users/{id}/purchase-history', [UserController::class, 'getPurchaseHistory'])->name('users.purchase_history');

    Route::resource('users', UserController::class);

    // product routes
    Route::get('products/most-purchased', [ProductController::class, 'getMostPurchasedProducts'])->name('products.most_purchased');
    Route::get('products/least-purchased', [ProductController::class, 'getLeastPurchasedProducts'])->name('products.least_purchased');
    Route::get('products/name-order-asc', [ProductController::class, 'sortByNameAsc'])->name('products.name_order_asc');
    Route::get('products/name-order-desc', [ProductController::class, 'sortByNameDesc'])->name('products.name_order_desc');
    Route::resource('products', ProductController::class);

    // order routes
    // routes/web.php
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/buy', [OrderController::class, 'buy'])->name('orders.buy');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::delete('/orders/delete-invalid', [OrderController::class, 'deleteInvalid'])->name('orders.deleteInvalid');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    
    



    //log routes
    Route::get('/logs', [UserActivityController::class, 'index'])->name('logs.index');
    Route::get('/logs/{id}', [UserActivityController::class, 'show'])->name('logs.show');
});
