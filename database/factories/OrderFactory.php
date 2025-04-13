<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

$factory->define(Order::class, function () {
    return [
        'user_id' => \App\Models\User::inRandomOrder()->first()->id,
        'address' => fake()->address(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->afterCreating(Order::class, function ($order) {
    $products = Product::inRandomOrder()->take(rand(1, 3))->get();
    $total = 0;

    foreach ($products as $product) {
        $quantity = rand(1, 5);
        $price = $product->price;
        $subtotal = $quantity * $price;

        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            'notes' => fake()->sentence(),
        ]);

        $total += $subtotal;
    }

    $order->update(['total_amount' => $total]);
});
