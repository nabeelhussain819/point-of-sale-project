<?php

namespace App\Traits;

use App\Core\Base;
use App\Helpers\StringHelper;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsActivityCustom
{
    use LogsActivity;

    protected static function bootLogsActivity(): void
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            return static::$eventName(function (Base $model) use ($eventName) {
                if (!$model->shouldLogEvent($eventName)) {
                    return;
                }

                $description = $model->getDescriptionForEvent($eventName);

                $logName = $model->getLogNameToUse($eventName);

                if ($description === '') {
                    return;
                }

                $attrs = $model->attributeValuesToBeLogged($eventName);


                if ($model->isLogEmpty($attrs) && !$model->shouldSubmitEmptyLogs()) {
                    return;
                }

                /** @var ActivityLogger $logger */
                $logger = app(ActivityLogger::class)
                    ->useLog($logName)
                    ->performedOn($model)
                    ->withProperties($attrs);

                if (method_exists($model, 'tapActivity')) {
                    $logger->tap([$model, 'tapActivity'], $eventName);
                }

                $logger->log($description);
            });
        });
    }

    /**
     * Getting 'Return value must be of the type string, null returned' error as we don't use config. Overriding and setting default value as empty string.
     *
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        if (isset(static::$logName)) {
            return static::$logName;
        }

        return '';
    }
}
