<?php declare(strict_types = 1);

namespace App\Policies;

use App\Models\{Category, User};

class CategoryPolicy
{
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    // public function update(User $user, Category $category): bool
    // {
    //     //
    // }

    // public function delete(User $user, Category $category): bool
    // {
    //     //
    // }
}
