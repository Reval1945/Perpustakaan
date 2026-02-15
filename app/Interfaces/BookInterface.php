<?php

namespace App\Interfaces;

use App\Models\Book;

interface BookInterface
{
    public function getAll();
    public function findById(string $id): ?Book;
    public function create(array $data): Book;
    public function update(Book $Book, array $data): Book;
    public function delete(Book $Book): bool;
}