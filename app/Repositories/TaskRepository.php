<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Task $model)
    {
        return parent::__construct($model);
    }

    public function getPaginatedTasks(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Task::with('category')
            ->whereHas('category', fn ($q) => $q->where('user_id', $user->id))
            ->when(
                // search: matches against title OR description
                // triggered when $filters['search'] is present and not empty
                isset($filters['search']) && filled($filters['search']),
                fn ($q) => $q->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%'.$filters['search'].'%')
                        ->orWhere('description', 'like', '%'.$filters['search'].'%');
                })
            )
            ->when(
                // status: exact match using the enum value e.g. 'pending', 'in_progress', 'completed'
                isset($filters['status']) && filled($filters['status']),
                fn ($q) => $q->where('status', $filters['status'])
            )
            ->when(
                // priority: exact match using the enum value e.g. 'low', 'medium', 'high'
                isset($filters['priority']) && filled($filters['priority']),
                fn ($q) => $q->where('priority', $filters['priority'])
            )
            ->when(
                // category_id: scope to a specific category (still owned by this user via the whereHas above)
                isset($filters['category_id']) && filled($filters['category_id']),
                fn ($q) => $q->where('category_id', $filters['category_id'])
            )

            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getByUser(User $user): Collection
    {
        return Task::whereHas('category', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();
    }

    public function getByStatus(User $user, TaskStatus $status): Collection
    {
        return Task::whereHas('category', fn ($q) => $q->where('user_id', $user->id))
            ->where('status', $status)
            ->latest()
            ->get();
    }
}
