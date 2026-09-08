<?php

namespace Modules\Admin\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Activity\ActivityLogDTO;
use Modules\Admin\Repositories\Contracts\ActivityLogRepositoryInterface;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class ActivityLogService implements ActivityLogServiceInterface
{
    public function __construct(
        protected ActivityLogRepositoryInterface $activityLogRepo
    ) {}

    /**
     * Record an audit or admin activity entry.
     */
    public function log(
        string $action,
        string $description,
        ?string $subjectType = null,
        ?string $subjectId = null,
        ?array $properties = []
    ): ActivityLog {
        $adminId = auth('admin')->id();
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        $dto = new ActivityLogDTO(
            action: $action,
            description: $description,
            adminId: $adminId,
            subjectType: $subjectType,
            subjectId: $subjectId,
            properties: $properties,
            ipAddress: $ip,
            userAgent: $userAgent
        );

        return $this->activityLogRepo->record($dto);
    }

    /**
     * Get recent activity logs for dashboard feed.
     */
    public function getRecentLogs(int $limit = 10): Collection
    {
        return $this->activityLogRepo->recent($limit);
    }

    /**
     * Get paginated audit logs.
     */
    public function getPaginatedLogs(int $perPage = 25): LengthAwarePaginator
    {
        return $this->activityLogRepo->paginate($perPage);
    }
}
