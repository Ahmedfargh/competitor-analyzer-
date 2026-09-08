<?php

namespace Modules\Admin\Repositories\Eloquent;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\ActivityLogDTOInterface;
use Modules\Admin\Repositories\Contracts\ActivityLogRepositoryInterface;

class EloquentActivityLogRepository implements ActivityLogRepositoryInterface
{
    /**
     * Get recent activity logs.
     */
    public function recent(int $limit = 50): Collection
    {
        return ActivityLog::with('admin')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get paginated activity logs.
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return ActivityLog::with('admin')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Record a new activity log.
     */
    public function record(ActivityLogDTOInterface $dto): ActivityLog
    {
        return ActivityLog::create($dto->toArray());
    }
}
