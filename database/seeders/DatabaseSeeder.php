<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 100 roles
        Role::factory(100)->create();

        // Make sure 'admin' role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create 100 users
        User::factory(100)->create();

        // Assign random roles to each user (via user_role pivot)
        User::all()->each(function ($user) {
            $roles = Role::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $user->roles()->sync($roles);
        });

        // Create 100 products
        Product::factory(100)->create();

        // Create 100 orders randomly assigned to users
        Order::factory(100)->create([
            'user_id' => User::inRandomOrder()->first()->id,
        ]);

        // Create 100 order details, randomly assigned to orders & products
        OrderDetail::factory(100)->create([
            'order_id' => Order::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
        ]);

        // Create a specific admin user
        $adminUser = User::factory()->create([
            'username' => 'admin',
            'email' => 'dangcaoanh1998@gmail.com',
            'password' => Hash::make('password'), // change this to a secure password
        ]);

        // Attach the 'admin' role to the user
        $adminUser->roles()->attach($adminRole->id);
    }
}
