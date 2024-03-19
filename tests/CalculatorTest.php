<?php

use Calculator\Calculator;
use PHPUnit\Framework\TestCase;

require_once 'src/bootstrap.php';

class CalculatorTest extends TestCase
{
    public function testDueTimeException()
    {
        $dueDate = new Calculator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The given date is not valid, or outside of working ours. Valid formats: 2023-01-01 12:12, 2023/01/01 12:12, 2023-01-01T12:12, 2023/01/01T12:12");
        $dueDate->CalculateDueTime('20230101', 16);
    }

    public function testTurnaroundTimeException()
    {
        $dueDate = new Calculator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The given tournaround time is not valid. It must be a number between 1 and 999");
        $dueDate->CalculateDueTime('2023-09-18T12:12', 'valami');
    }

    public function testCalculateDueTime()
    {
        $dueTime = new Calculator();

        // Submit date is Thursday and the turnaround time is 168 hours (4 weeks and 1 day)
        $this->assertEquals('2023-10-13 12:12', $dueTime->CalculateDueTime('2023-09-14 12:12', 168));

    }
}
