<?php

namespace Calculator;

use DateInterval;
use DateTime;
use InvalidArgumentException;

class Calculator
{
    private $workingHoursStart = 9;
    private $workingHoursEnd = 17;

    public function CalculateDueTime($submitDateTime, $turnaroundTime)
    {
        // Validate the submitted date
        if (!$this->validateDate($submitDateTime)) {
            throw new InvalidArgumentException("The given date is not valid. Please use the following format: 2023-01-01 12:12 or 2023-01-01T12:12:12");
        }

        //Validate the tournaround time
        if (!$this->validateTime($turnaroundTime)) {
            throw new InvalidArgumentException("The given tournaround time is not valid. It must be a number between 0 and 999");
        }

        // Parse the submit date/time
        $submitDate = new DateTime($submitDateTime);

        $weeks = (int) floor($turnaroundTime / 40);
        $turnaroundTime = $turnaroundTime - ($weeks * 40);

        $days = (int) floor($turnaroundTime / 8);
        $turnaroundTime = $turnaroundTime - ($days * 8);

        if ($weeks > 0) {
            $submitDate->add(new DateInterval('P' . $weeks . 'W'));
        }

        while ($days > 0) {
            $currentDay = $submitDate->format('N');
            if ($currentDay <= 5) {
                $currentHour = $submitDate->format('G');
                if ($currentHour >= $this->workingHoursStart && $currentHour < $this->workingHoursEnd) {
                    $days--;
                }
            }
            $submitDate->add(new DateInterval('P1D'));
        }

        // Return the resolved date/time
        return $submitDate->format('Y-m-d H:i');
    }

    public function validateDate($value)
    {
        if (preg_match('/^20\d{2}(-|\/)((0[1-9])|(1[0-2]))(-|\/)((0[1-9])|([1-2][0-9])|(3[0-1]))(T| )(09|(1[0-7])):([0-5][0-9])$/', $value)) {
            $date = date_create($value);
            $day = $date->format('N');
            if ($date->format('N') <= 5) {
                return $value;
            }
        }
        return false;
    }

    public function validateTime($value)
    {
        if (preg_match('/^[0-9]{1,3}$/', $value) && (int) $value > 0) {
            return $value;
        }
        return false;
    }

}
