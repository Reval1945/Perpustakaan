<?php

namespace App\Interfaces;

use App\Models\User;

interface UserInterface
{
    public function getAll(array $filters = []);
    public function findById(string $id): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;
}
