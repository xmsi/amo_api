<?php

namespace Tests\Unit\Utils;

use App\Services\Utils\MyDateTime as UtilsMyDateTime;
use DateTime;
use PHPUnit\Framework\TestCase;

class MyDateTimeTest extends TestCase
{
    public function testThuresday()
    {
        $initialDate = '2024-07-09 12:06:02'; 
        $expectedDate = '2024-07-15 12:06:02';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testWednesdayEarly()
    {
        $initialDate = '2024-07-03 02:00:00';
        $expectedDate = '2024-07-09 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testWednesdayEvening()
    {
        $initialDate = '2024-07-03 22:00:00';
        $expectedDate = '2024-07-10 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testWednesday()
    {
        $initialDate = '2024-07-03 12:00:00';
        $expectedDate = '2024-07-09 12:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testSundayEarlyMorning()
    {
        $initialDate = '2024-07-07 08:00:00';
        $expectedDate = '2024-07-12 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testSundayEvening()
    {
        $initialDate = '2024-07-07 23:00:00';
        $expectedDate = '2024-07-12 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testSunday()
    {
        $initialDate = '2024-07-07 10:00:00';
        $expectedDate = '2024-07-12 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testSaturday()
    {
        $initialDate = '2024-07-06 02:00:00';
        $expectedDate = '2024-07-12 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testFridayAfternoon()
    {
        $initialDate = '2024-07-05 15:00:00';
        $expectedDate = '2024-07-11 15:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testFridayEvening()
    {
        $initialDate = '2024-07-05 20:00:00';
        $expectedDate = '2024-07-12 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testMondayEvening()
    {
        $initialDate = '2024-07-01 22:40:10';
        $expectedDate = '2024-07-08 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }

    public function testMondayMorning()
    {
        $initialDate = '2024-07-01 02:40:10';
        $expectedDate = '2024-07-05 09:00:00';
        $this->assertEquals($expectedDate, UtilsMyDateTime::addWorkingDays(4, $initialDate));
    }
}
