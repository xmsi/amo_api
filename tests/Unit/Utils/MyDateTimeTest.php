<?php

namespace Tests\Unit\Utils;

use App\Services\Utils\MyDateTime as UtilsMyDateTime;
use DateTime;
use PHPUnit\Framework\TestCase;

class MyDateTimeTest extends TestCase
{
    public function testThursday()
    {
        $initialDate = '2024-07-09 12:06:02'; // Assuming this is a Tuesday
        $expectedDate = '2024-07-15 12:06:02'; // 4 working days later (skipping the weekend)
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testWorkingHoursStart()
    {
        $initialDate = '2024-07-09 08:00:00'; // Before working hours on a Tuesday
        $expectedDate = '2024-07-11 09:00:00'; // 2 working days later (starting from 9:00 on Tuesday)
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(2, $initialDate));
    }
}
