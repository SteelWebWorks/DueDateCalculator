<?php

use Calculator\Calculator;
use PHPUnit\Framework\TestCase;

require_once 'src/bootstrap.php';

class CalculatorTest extends TestCase
{
    public function testValidateDateTime()
    {
        $dueDate = new Calculator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The given date is not valid. Please use the following format: 2023-01-01 12:12 or 2023-01-01T12:12:12");
        $dueDate->CalculateDueTime('20230101', 16);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The given date is not valid. Please use the following format: 2023-01-01 12:12 or 2023-01-01T12:12:12");
        $dueDate->CalculateDueTime('2023-09-18121212', 16);
    }

    public function testCalidateTurnAroundTime()
    {
        $dueDate = new Calculator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The given tournaround time is not valid. It must be a number between 0 and 999");
        $dueDate->CalculateDueTime('2023-09-18T12:12', 'valami');
    }

    public function testCalculateDueTime()
    {
        $dueTime = new Calculator();

        // Submit date is monday and the turn around time is 16 (2 days)
        $this->assertEquals('2023-09-20 12:12', $dueTime->CalculateDueTime('2023-09-18 12:12', '16'));

        // Submit date is friday and the turnaround time is 16 (2 days)
        $this->assertEquals('2023-09-19 09:08', $dueTime->CalculateDueTime('2023-09-15 09:08', '16'));

        // Submit date is Thursday and the turnaround time is 168 hours (4 weeks and 1 day)
        $this->assertEquals('2023-10-13 12:12', $dueTime->CalculateDueTime('2023-09-14 12:12', 168));

    }
}
