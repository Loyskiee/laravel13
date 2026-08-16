<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model) {}

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(array $attributes, Model $model): Model
    {
        $model->update($attributes);

        return $model->fresh();
    }

    /**
     * Directly delete the model
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}
