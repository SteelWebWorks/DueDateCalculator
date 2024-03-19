<?php

use Calculator\Calculator;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/bootstrap.php';

class CalculatorTest extends TestCase
{
    public function testDueTimeException()
    {
        $dueDate = new Calculator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The given date is not valid, or outside of working ours. Valid formats: 2024-01-01 12:12, 2024/01/01 12:12, 2024-01-01T12:12, 2024/01/01T12:12");
        $dueDate->CalculateDueTime('20240101', 16);
    }

    public function testTurnaroundTimeException()
    {
        $dueDate = new Calculator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The given tournaround time is not valid. It must be a number between 1 and 999");
        $dueDate->CalculateDueTime('2024-01-18T12:12', 'valami');
    }

    public function testCalculateDueTime()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-12 13:12', $dueTime->CalculateDueTime('2024-01-12 12:12', 1));

    }

    public function testCalculateDueTimeShortTime()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-15 15:12', $dueTime->CalculateDueTime('2024-01-15 12:12', 3));

    }

    public function testCalculateDueTimeShortTimeWeekend()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-15 11:12', $dueTime->CalculateDueTime('2024-01-12 16:12', 3));

    }

    public function testCalculateDueMedium()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-16 09:12', $dueTime->CalculateDueTime('2024-01-15 12:12', 5));

    }

    public function testCalculateDueEightHours()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-16 09:15', $dueTime->CalculateDueTime('2024-01-15 9:15', 8));

    }

    public function testCalculateDueEightHoursAtStart()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-22 17:00', $dueTime->CalculateDueTime('2024-01-22 9:00', 8));

    }

    public function testCalculateDueFullWorkTimeAtFriday()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-19 17:00', $dueTime->CalculateDueTime('2024-01-19 9:00', 8));

    }

    public function testCalculateLong()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-17 10:00', $dueTime->CalculateDueTime('2024-01-15 9:00', 17));

    }

    public function testCalculateWeryLong()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-29 12:13', $dueTime->CalculateDueTime('2024-01-15 10:13', 82));
    }

    public function testCalculateSkipWeekend()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-15 10:13', $dueTime->CalculateDueTime('2024-01-12 10:13', 8));
    }

    public function testDateFormatOne()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-16 09:12', $dueTime->CalculateDueTime('2024-01-15T12:12', 5));
    }

    public function testDateTimeFormatTwo()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-16 09:12', $dueTime->CalculateDueTime('2024/01/15T12:12', 5));
    }

    public function testDateTimeFormatThree()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-16 09:12', $dueTime->CalculateDueTime('2024/01/15 12:12', 5));
    }

}
