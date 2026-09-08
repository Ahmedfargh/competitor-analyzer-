<?php

namespace Modules\Admin\Repositories\Contracts;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\ActivityLogDTOInterface;

interface ActivityLogRepositoryInterface
{
    /**
     * Get recent activity logs.
     */
    public function recent(int $limit = 50): Collection;

    /**
     * Get paginated activity logs.
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator;

    /**
     * Record a new activity log.
     */
    public function record(ActivityLogDTOInterface $dto): ActivityLog;
}
