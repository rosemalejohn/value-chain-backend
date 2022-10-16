<?php

namespace App\Enums;

enum TaskAssignmentRole: int implements HasDescription
{
    case Contributor = 1;
    case Manager = 2;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Contributor => 'Contributor',
            self::Manager => 'Manager',
        };
    }
}
