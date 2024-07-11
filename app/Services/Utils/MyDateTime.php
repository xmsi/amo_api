<?php

namespace App\Services\Utils;

use App\Services\AmoCrm\Constants\MyDateTime as DateTimeData;
use DateTime;

class MyDateTime
{
    public static function addWorkingDays(int $daysToAdd, ?string $date = null): string
    {
        $workingHoursStart = DateTimeData::WORK_START_HOUR;
        $workingHoursEnd = DateTimeData::WORK_END_HOUR;
        date_default_timezone_set(DateTimeData::TIMEZONE);
        $currentDateTime = is_null($date) ? new DateTime() : new DateTime($date);

        // Проверяем время 9 < x < 18.
        $hour = (int)$currentDateTime->format('H');
        if ($hour < $workingHoursStart) {
            $currentDateTime->setTime($workingHoursStart, 0);
        } elseif ($hour >= $workingHoursEnd) {
            $currentDateTime->modify('+1 day')->setTime($workingHoursStart, 0);
        }

        $currentDateTime->modify('+' . $daysToAdd . ' day');
        self::weekendNormalize($currentDateTime);

        return $currentDateTime->format('Y-m-d H:i:s');
    }

    /**
     *  Если суббота + 2 дня если воскресение + 1 день. Для начала работы
     */
    private static function weekendNormalize(DateTime $date): void
    {
        if ((int)$date->format('N') === 6) {
            $date->modify('+2 day');
        } elseif ((int)$date->format('N') === 7) {
            $date->modify('+1 day');
        }
    }
}
