<?php

namespace Calculator;

use DateTime;
use InvalidArgumentException;

class Calculator
{
    public function CalculateDueTime($submitDateTime, $submittedTurnaroundTime)
    {
        // Validate the submitted date
        $dateTime = $this->validateDate($submitDateTime);

        //Validate the tournaround time
        $turnaroundTime = $this->validateTime($submittedTurnaroundTime);

        // Parse the submit date/time
        $submitDate = new DateTime($dateTime);

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
                if ($currentHour >= 9 && $currentHour < 17) {
                    $days--;
                }
            }
            $submitDate->modify('+1 day');
        }
        while ($turnaroundTime > 0) {
            $submitDate->modify('+1 hour');
            $currentDay = $submitDate->format('N');
            if ($currentDay <= 5) {
                $currentHour = $submitDate->format('G');
                if ($currentHour >= 9 && $currentHour < 17) {
                    $turnaroundTime--;
                }
            }
        }
        // Return the resolved date/time
        return $submitDate->format('Y-m-d H:i');
    }

    public function validateDate($value)
    {
        if (preg_match('/^20\d{2}(-|\/)((0[1-9])|(1[0-2]))(-|\/)((0[1-9])|([1-2][0-9])|(3[0-1]))(T| )(([0-1][0-9]|(2[0-3]))):([0-5][0-9])$/', $value)) {
            $date = new DateTime($value);
            if (in_array($date->format('l'), ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])
                && ($date->format('G') >= 9 && $date->format('G') < 17)) {
                return $value;
            }
        }
        throw new InvalidArgumentException("The given date is not valid, or outside of working ours. Valid formats: 2023-01-01 12:12, 2023/01/01 12:12, 2023-01-01T12:12, 2023/01/01T12:12");
    }

    public function validateTime($value)
    {
        if (preg_match('/^[0-9]{1,3}$/', $value)) {
            return $value;
        }
        throw new InvalidArgumentException("The given tournaround time is not valid. It must be a number between 1 and 999");
    }

}
