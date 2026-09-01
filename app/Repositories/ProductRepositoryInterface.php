<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginatedProducts(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator;
}
