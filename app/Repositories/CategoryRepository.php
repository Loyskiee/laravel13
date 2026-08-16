<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        return parent::__construct($model);
    }

    /**
     * Paginate the user's Categories
     * $user is an object, so access the id to fetch the user from the database
     */
    public function getPaginatedCategory(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Category::where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }
}
