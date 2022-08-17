<?php

namespace App\Enums;

enum TaskPriority: int implements HasDescription
{
    case Urgent = 1;
    case High = 2;
    case Normal = 3;
    case Low = 4;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Urgent => 'Urgent',
            self::High => 'High',
            self::Normal => 'Normal',
            self::Low => 'Low'
        };
    }
}
