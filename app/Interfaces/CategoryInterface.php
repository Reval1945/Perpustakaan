<?php

namespace App\Interfaces;

use App\Models\Category;

interface CategoryInterface
{
    public function getAll();
    public function findById(string $id): ?Category;
    public function create(array $data): Category;
    public function update(Category $Category, array $data): Category;
    public function delete(Category $Category): bool;
}
