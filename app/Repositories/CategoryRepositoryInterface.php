<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginatedCategory(User $user, int $perPage = 10): LengthAwarePaginator;
}
