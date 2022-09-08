<?php

namespace App\Enums;

enum TaskImpact: int implements HasDescription
{
    case Low = 1;
    case Medium = 2;
    case High = 3;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::High => 'High',
            self::Medium => 'Medium',
            self::Low => 'Low'
        };
    }
}
