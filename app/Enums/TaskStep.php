<?php

namespace App\Enums;

enum TaskStep: int implements HasDescription
{
    case Measurement = 1;
    case Testing = 2;
    case Staging = 3;
    case Deployment = 4;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Measurement => 'Measure',
            self::Testing => 'Testing',
            self::Staging => 'Staging',
            self::Deployment => 'Deployment',
        };
    }
}
