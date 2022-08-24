<?php

namespace App\Enums;

enum TaskPriority: int implements HasDescription
{
    case Low = 1;
    case Normal = 2;
    case High = 3;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::High => 'High',
            self::Normal => 'Normal',
            self::Low => 'Low'
        };
    }
}
