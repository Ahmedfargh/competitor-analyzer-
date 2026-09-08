<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class AdminActivityLogController extends Controller
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Display a listing of system activity logs.
     */
    public function index(): View
    {
        $logs = $this->activityLogService->getPaginatedLogs(25);

        return view('admin::activity-logs.index', [
            'logs' => $logs,
        ]);
    }
}
