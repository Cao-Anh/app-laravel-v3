<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class UserPolicy
{
    // Helper method to get user's highest role level
    protected function getRoleLevel(User $user)
    {
        // Assuming roles are ordered by permission level (admin highest, Member lowest)
        $roleLevels = [
            'admin' => 4,
            'manager' => 3,
            'leader' => 2,
            'Member' => 1
        ];

        $highestLevel = 0;
        foreach ($user->roles as $role) {
            $level = $roleLevels[$role->name] ?? 0;
            if ($level > $highestLevel) {
                $highestLevel = $level;
            }
        }

        return $highestLevel;
    }

    // Helper to check if target user has same or lower level
    protected function hasSameOrLowerLevel(User $currentUser, User $targetUser)
    {
        $currentLevel = $this->getRoleLevel($currentUser);
        $targetLevel = $this->getRoleLevel($targetUser);

        return $targetLevel <= $currentLevel;
    }

    public function viewAny(User $user)
    {
        // All roles can view users
        return true;
    }

    public function view(User $user, User $model)
    {
        // All roles can view users
        return true;
    }

    public function create(User $user)
    {
        // Only admin can create users
        return $user->roles->contains('name', 'admin');
    }

    public function update(User $user, User $model)
    {
        // admin can update any user
        if ($user->roles->contains('name', 'admin')) {
            return true;
        }

        // manager can update users with same or lower level
        if ($user->roles->contains('name', 'manager')) {
            return $this->hasSameOrLowerLevel($user, $model);
        }

        // leader can update users with same or lower level
        if ($user->roles->contains('name', 'leader')) {
            return $this->hasSameOrLowerLevel($user, $model);
        }

        return false;
    }

    public function delete(User $user, User $model)
    {
        // admin can delete any user
        if ($user->roles->contains('name', 'admin')) {
            return true;
        }

        // manager can delete users with same or lower level
        if ($user->roles->contains('name', 'manager')) {
            return $this->hasSameOrLowerLevel($user, $model);
        }

        return false;
    }
}