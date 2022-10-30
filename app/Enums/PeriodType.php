<?php

namespace App\Enums;

enum PeriodType: string implements HasDescription
{
    case Hour = 'h';
    case Day = 'd';
    case Week = 'w';
    case Month = 'm';
    case Year = 'y';

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Hour => 'Hour',
            self::Day => 'Day',
            self::Week => 'Week',
            self::Month => 'Month',
            self::Year => 'Year'
        };
    }
}
