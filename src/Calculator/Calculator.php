<?php

namespace Calculator;

use DateTime;
use InvalidArgumentException;

class Calculator
{
    private $workingHoursStart = 9;
    private $workingHoursEnd = 17;

    private $workingDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    public function CalculateDueTime($submitDateTime, $turnaroundTime)
    {
        // Validate the submitted date
        if (!$this->validateDate($submitDateTime)) {
            throw new InvalidArgumentException("The given date is not valid, or outside of working ours. Valid formats: 2023-01-01 12:12, 2023/01/01 12:12, 2023-01-01T12:12, 2023/01/01T12:12");
        }

        //Validate the tournaround time
        if (!$this->validateTime($turnaroundTime)) {
            throw new InvalidArgumentException("The given tournaround time is not valid. It must be a number between 1 and 999");
        }

        // Parse the submit date/time
        $submitDate = new DateTime($submitDateTime);

        $weeks = (int) floor($turnaroundTime / 40);
        $turnaroundTime = $turnaroundTime - ($weeks * 40);

        $days = (int) floor($turnaroundTime / 8);
        $turnaroundTime = $turnaroundTime - ($days * 8);

        if ($weeks > 0) {
            $submitDate->modify("+{$weeks} weeks");
        }

        while ($days > 0) {
            $currentDay = $submitDate->format('N');
            if ($currentDay <= 5) {
                $currentHour = $submitDate->format('G');
                if ($currentHour >= $this->workingHoursStart && $currentHour < $this->workingHoursEnd) {
                    $days--;
                }
            }
            $submitDate->modify('+1 day');
        }

        // Return the resolved date/time
        return $submitDate->format('Y-m-d H:i');
    }

    public function validateDate($value)
    {
        if (preg_match('/^20\d{2}(-|\/)((0[1-9])|(1[0-2]))(-|\/)((0[1-9])|([1-2][0-9])|(3[0-1]))(T| )(([0-1][0-9]|(2[0-3]))):([0-5][0-9])$/', $value)) {
            $date = new DateTime($value);
            if (in_array($date->format('l'), $this->workingDays)
                && ($date->format('G') >= $this->workingHoursStart && $date->format('G') < $this->workingHoursEnd)) {
                return $value;
            }
        }
        return false;
    }

    public function validateTime($value)
    {
        if (preg_match('/^[1-9]{1,3}$/', $value)) {
            return $value;
        }
        return false;
    }

}
