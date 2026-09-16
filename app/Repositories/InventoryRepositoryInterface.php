<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface InventoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getRecentForUser(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function createMovement(array $attributes): Inventory;
}
