<?php

namespace App\Enums;

enum UserRole: string implements HasDescription
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Developer = 'member';
    case Measurement = 'measurement';
    case AbTester = 'ab_tester';
    case Staging = 'staging';
    case Deployment = 'deployment';

    /**
     * Enum description
     */
    public function description(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Manager => 'Manager',
            self::Developer => 'Developer',
            self::Measurement => 'Measurement',
            self::AbTester => 'AB Tester',
            self::Staging => 'Staging',
            self::Deployment => 'Deployer',
        };
    }
}
