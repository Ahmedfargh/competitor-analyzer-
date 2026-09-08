<?php

namespace App\Livewire\Admin\ActivityLogs;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogFeed extends Component
{
    use WithPagination;

    public string $search = '';

    public string $actionFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActionFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ActivityLog::with('admin')->latest();

        if (! empty($this->search)) {
            $search = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', $search)
                    ->orWhere('ip_address', 'like', $search)
                    ->orWhere('action', 'like', $search);
            });
        }

        if (! empty($this->actionFilter)) {
            $query->where('action', $this->actionFilter);
        }

        $logs = $query->paginate(20);
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('livewire.admin.activity-logs.activity-log-feed', [
            'logs' => $logs,
            'actions' => $actions,
        ]);
    }
}
