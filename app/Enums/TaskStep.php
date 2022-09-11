<?php

namespace App\Enums;

enum TaskStep: int implements HasDescription
{
    case Measurement = 1;
    case AbTesting = 2;
    case Staging = 3;
    case QaTesting = 4;
    case Deployment = 5;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Measurement => 'Measure',
            self::AbTesting => 'AB Testing',
            self::Staging => 'Staging',
            self::QaTesting => 'QA Testing',
            self::Deployment => 'Deployment',
        };
    }
}
