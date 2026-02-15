<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryInterface;

class CategoryRepository implements CategoryInterface
{
    public function getAll()
    {
        return Category::latest()->get();
    }

    public function findById(string $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $Category, array $data): Category
    {
        $Category->update($data);
        return $Category;
    }

    public function delete(Category $Category): bool
    {
        return $Category->delete();
    }
}
