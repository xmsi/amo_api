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


        // Проверяем если сделка в дни отдыха
        if($currentDateTime->format('N') > 5){
            $currentDateTime = self::weekendNormalize($currentDateTime);

            $currentDateTime->setTime($workingHoursStart, 0);
            $currentDateTime->modify("+$daysToAdd day");

            return $currentDateTime->format('Y-m-d H:i:s');
        }

        // Проверяем время 9 < x < 18. 2ое условие для пятницы вечера
        $hour = (int)$currentDateTime->format('H');
        if ($hour < $workingHoursStart) {
            $currentDateTime->setTime($workingHoursStart, 0);
        } elseif ($hour >= $workingHoursEnd && $currentDateTime->format('N') == 5) {
            $currentDateTime->modify('+3 day')->setTime($workingHoursStart, 0);
        } elseif ($hour >= $workingHoursEnd) {
            $currentDateTime->modify('+1 day')->setTime($workingHoursStart, 0);
        } 

        // Обычная проверка
        $daysAdded = 0;
        while ($daysAdded < $daysToAdd) {
            $currentDateTime->modify('+1 day');
            $currentDateTime = self::weekendNormalize($currentDateTime);
            $daysAdded++;
        } 
    
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