<?php

namespace App\Services\Utils;

use DateTime;

class MyDateTime 
{
    public static function addWorkingDays(int $daysToAdd, ?string $date = null): string {
        $workingHoursStart = 9; 
        $workingHoursEnd = 18; 
        date_default_timezone_set('Asia/Tashkent');
        $currentDateTime = is_null($date) ? new DateTime() : new DateTime($date);

        // Проверяем время 9 < x < 18.
        $hour = (int)$currentDateTime->format('H');
        if ($hour < $workingHoursStart) {
            $currentDateTime->setTime($workingHoursStart, 0);
        } elseif ($hour >= $workingHoursEnd) {
            $currentDateTime->modify('+1 day')->setTime($workingHoursStart, 0);
        } 

        $currentDateTime->modify('+'.$daysToAdd.' day');
        self::weekendNormalize($currentDateTime);
    
        return $currentDateTime->format('Y-m-d H:i:s');
    }

    /**
     *  Если суббота + 2 дня если воскресение + 1 день. Для начала работы
     */
    private static function weekendNormalize(DateTime $date): DateTime{
        if($date->format('N') == 6){
            $date->modify('+2 day');
        } elseif($date->format('N') == 7) {
            $date->modify('+1 day');
        }
        
        return $date;
    }
}