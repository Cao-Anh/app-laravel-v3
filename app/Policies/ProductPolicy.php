<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    public function viewAny(User $user)
    {
        // All roles can view products
        return true;
    }

    public function view(User $user, Product $product)
    {
        // All roles can view products
        return true;
    }

    public function create(User $user)
    {
        // Only admin can create products
        return $user->roles->contains('name', 'admin');
    }

    public function update(User $user)
    {
        // admin and manager can update products
        if ($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'manager')) {
            return true;
        }

        // leader can update products
        if ($user->roles->contains('name', 'leader')) {
            return true;
        }

        return false;
    }

    public function delete(User $user)
    {
        // admin and manager can delete products
        return $user->roles->contains('name', 'admin') || $user->roles->contains('name', 'manager');
    }
}