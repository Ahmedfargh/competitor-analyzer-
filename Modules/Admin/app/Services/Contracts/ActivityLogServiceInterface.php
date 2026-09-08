<?php

namespace Modules\Admin\Services\Contracts;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ActivityLogServiceInterface
{
    /**
     * Record an audit or admin activity entry.
     */
    public function log(
        string $action,
        string $description,
        ?string $subjectType = null,
        ?string $subjectId = null,
        ?array $properties = []
    ): ActivityLog;

    /**
     * Get recent activity logs for dashboard feed.
     */
    public function getRecentLogs(int $limit = 10): Collection;

    /**
     * Get paginated audit logs.
     */
    public function getPaginatedLogs(int $perPage = 25): LengthAwarePaginator;
}
