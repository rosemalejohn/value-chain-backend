<?php

namespace App\Enums;

enum TaskStatus: int implements HasDescription
{
    case Pending = 1;
    case Accepted = 2;
    case Rejected = 3;
    case Backlog = 4;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Backlog => 'Backlog',
        };
    }
}
