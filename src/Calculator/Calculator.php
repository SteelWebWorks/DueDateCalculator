<?php

namespace Calculator;

use DateTime;
use Exception;
use InvalidArgumentException;

class Calculator
{

    protected $dayStart = 9;
    protected $dayEnd = 17;

    public function CalculateDueTime(string $submitDateTime, $submittedTurnaroundTime)
    {
        // Validate the submitted date
        $dateTime = $this->validateDate($submitDateTime);

        //Validate the tournaround time
        $turnaroundTime = $this->validateTime($submittedTurnaroundTime);

        // Parse the submit date/time
        $date = new DateTime($dateTime);
        $timeStamp = $date->getTimestamp();

        while ($turnaroundTime > 0) {
            $turnaroundTime--;
            $timeStamp += 3600;

            $hour = $this->numHour($timeStamp);
            $min = (int) date('i', $timeStamp);

            if ($hour > $this->dayEnd || ($hour == $this->dayEnd && ($min > 0 || $turnaroundTime > 0))) {
                $timeStamp += ($this->dayStart + (24 - $hour)) * 3600;
            } elseif ($hour < $this->dayStart) {
                $timeStamp = ($this->dayStart - $hour) * 3600;
            }

            if ($this->isWeekend($timeStamp)) {
                $timeStamp += (8 - $this->weekDay($timeStamp)) * 24 * 3600;
            }
        }
        // Return the resolved date/time
        $date->setTimestamp($timeStamp);
        return $date->format('Y-m-d H:i');
    }

    private function numHour(int $time): int
    {
        return (int) date('G', $time);
    }

    private function isWeekend(int $time): bool
    {
        return $this->weekDay($time) > 5;
    }
    private function weekDay(int $time): int
    {
        return (int) date('N', $time);
    }
    private function validateDate(string $value): string | Exception
    {
        if (preg_match('/^20\d{2}(-|\/)((0[1-9])|(1[0-2]))(-|\/)((0[1-9])|([1-2][0-9])|(3[0-1]))(T| )([01]?[0-9]|2[0-3]):([0-5][0-9])$/', $value)) {
            $date = new DateTime($value);
            if ($this->weekDay($date->getTimestamp())
                && ($date->format('G') >= $this->dayStart && $date->format('G') < $this->dayEnd)) {
                return $value;
            }
        }
        throw new InvalidArgumentException("The given date is not valid, or outside of working ours. Valid formats: 2024-01-01 12:12, 2024/01/01 12:12, 2024-01-01T12:12, 2024/01/01T12:12");
    }

    private function validateTime(string $value): string | Exception
    {
        if (preg_match('/^[0-9]{1,3}$/', $value)) {
            return $value;
        }
        throw new InvalidArgumentException("The given tournaround time is not valid. It must be a number between 1 and 999");
    }

}
