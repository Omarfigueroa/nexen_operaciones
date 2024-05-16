<?php

namespace App\Models;

interface IModel
{
    public function all(): array;
    public function store(array $data): \MongoDB\InsertOneResult;
    public function findById($id, array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByIds(array $data);
}