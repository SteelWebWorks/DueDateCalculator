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

    public function testCalculateDueTimeShortOne()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-12 13:12', $dueTime->CalculateDueTime('2024-01-12 12:12', 1));

    }

    public function testCalculateDueTimeShortTwho()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-15 15:12', $dueTime->CalculateDueTime('2024-01-15 12:12', 3));

    }

    public function testCalculateDueMediumOne()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-16 09:12', $dueTime->CalculateDueTime('2024-01-15 12:12', 5));

    }

    public function testCalculateDueMediumTwo()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-16 09:15', $dueTime->CalculateDueTime('2024-01-15 9:15', 8));

    }

    public function testCalculateLongOne()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-17 10:00', $dueTime->CalculateDueTime('2024-01-15 9:00', 17));

    }

    public function testCalculateLongTwo()
    {
        $dueTime = new Calculator();

        $this->assertEquals('2024-01-29 12:13', $dueTime->CalculateDueTime('2024-01-15 10:13', 82));
    }

}
