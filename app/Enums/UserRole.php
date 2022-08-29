<?php

namespace App\Enums;

enum UserRole: string implements HasDescription
{
    case Admin = 'admin';
    case Measurement = 'measurement';
    case AbTester = 'tester';
    case Staging = 'staging';
    case Deployment = 'deployment';

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Measurement => 'Measurement',
            self::AbTester => 'AB Tester',
            self::Staging => 'Staging',
            self::Deployment => 'Deployer',
        };
    }
}
