<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function all();

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): Model;

    public function create(array $attributes): Model;

    public function update(array $attributes, Model $model): Model;

    public function delete(Model $model): bool;
}
