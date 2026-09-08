<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

trait LogsActivity
{
    /**
     * Boot the activity logging trait for a model.
     */
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            static::recordModelActivity('created', $model);
        });

        static::updated(function (Model $model) {
            static::recordModelActivity('updated', $model);
        });

        static::deleted(function (Model $model) {
            static::recordModelActivity('deleted', $model);
        });
    }

    /**
     * Record an activity log entry for the given model event.
     */
    protected static function recordModelActivity(string $event, Model $model): void
    {
        $logService = app(ActivityLogServiceInterface::class);
        $modelName = class_basename($model);
        $action = strtolower($modelName).'.'.$event;
        $key = $model->getKey();

        $properties = $event === 'updated'
            ? $model->getChanges()
            : ($event === 'deleted' ? ['id' => $key] : $model->toArray());

        $logService->log(
            action: $action,
            description: "{$modelName} #{$key} was {$event}.",
            subjectType: get_class($model),
            subjectId: (string) $key,
            properties: $properties
        );
    }
}
