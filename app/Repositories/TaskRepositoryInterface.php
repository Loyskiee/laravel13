<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginatedTasks(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function getByUser(User $user): Collection;

    public function getByStatus(User $user, TaskStatus $status): Collection;
}
