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
        // Create 4 roles
        $roles = [
            ['name' => 'admin', 'description' => 'Has full access to the system.'],
            ['name' => 'manager', 'description' => 'Manages teams and projects.'],
            ['name' => 'leader', 'description' => 'Leads and supervises members.'],
            ['name' => 'member', 'description' => 'Standard user access.'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

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
        // Create 100 orders with details and correct total_amount
        $users = User::all();
        $products = Product::all();

        for ($i = 0; $i < 100; $i++) {
            $order = Order::create([
                'user_id' => $users->random()->id,
                'address' => fake()->address,
                'total_amount' => 0, // Temporary
            ]);

            $totalAmount = 0;

            $randomProducts = $products->random(rand(1, 4));
            foreach ($randomProducts as $product) {
                $quantity = rand(1, 5);
                $subtotal = $product->price * $quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'notes' => fake()->sentence,
                ]);

                $totalAmount += $subtotal;
            }

            $order->update(['total_amount' => $totalAmount]);
        }



        // Create a specific admin user
        $adminUser = User::factory()->create([
            'username' => 'admin',
            'email' => 'dangcaoanh1998@gmail.com',
            'password' => Hash::make('1234A'), // change this to a secure password
        ]);

        // Attach the 'admin' role to the user
        $adminUser->roles()->attach($adminRole->id);
    }
}
