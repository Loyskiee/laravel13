<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getPaginatedProducts(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Product::where('user_id', $user->id)
            ->with('category:id,name')
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($q) => $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%")))
            ->when($filters['category_id'] ?? null, fn ($q, $id) => $q->where('category_id', $id))
            ->when($filters['stock_status'] ?? null, fn ($q, $status) => match ($status) {
                'out' => $q->where('quantity', 0),
                'low' => $q->where('quantity', '>', 0)->whereColumn('quantity', '<=', 'minimum_stock'),
                'in' => $q->whereColumn('quantity', '>', 'minimum_stock'),
                default => $q
            })
            ->latest()->paginate($perPage)->withQueryString();
    }
}
