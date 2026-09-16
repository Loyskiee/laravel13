<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;


class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    public function __construct(Inventory $model)
    {
        parent::__construct($model);
    }

    public function getRecentForUser(User $user, array $filters = [], $perPage = 10): LengthAwarePaginator
    {
        return Inventory::where('user_id', $user->id)
            ->with(['product:id,name,sku', 'user:id,name'])
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($q) => $q->where('reason', 'like', "%$s%")->orWhereHas('product', fn ($q) => $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%"))))
            ->when($filters['type'] ?? null, fn ($q, $type) => $q->where('type', $type))
            ->when($filters['product_id'] ?? null, fn ($q, $id) => $q->where('product_id', $id))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
    public function createMovement(array $attributes): Inventory
    {
        return $this->model->create($attributes);
    }
}
