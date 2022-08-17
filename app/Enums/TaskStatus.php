<?php

namespace App\Enums;

enum TaskStatus: int implements HasDescription
{
    case Pending = 1;
    case Accepted = 2;
    case Rejected = 3;
    case InProgress = 4;
    case Testing = 5;
    case ForDeployment = 6;
    case Deployed = 7;
    case Backlog = 8;

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::InProgress => 'In Progress',
            self::Testing => 'Testing',
            self::ForDeployment => 'For Deployment',
            self::Deployed => 'Deployed',
            self::Backlog => 'Backlog'
        };
    }
}
