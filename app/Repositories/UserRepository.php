<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    public function getAll(array $filters = [])
    {
        $query = User::query()->latest();

        $query->when($filters['email'] ?? null, function ($q, $email) {
            $q->where('email', 'like', "%{$email}%");
        });

        $query->when($filters['name'] ?? null, function ($q, $name) {
            $q->where('name', 'like', "%{$name}%");
        });

        $query->when($filters['role'] ?? null, function ($q, $role) {
            $q->where('role', $role);
        });

        $query->when($filters['class'] ?? null, function ($q, $class) {
            $q->where('class', 'like', "%{$class}%");
        });

        return $query->get();
    }

    public function findById(string $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
