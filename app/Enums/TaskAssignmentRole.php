<?php

namespace App\Enums;

enum TaskAssignmentRole: int
{
    case Contributor = 1;
    case Manager = 2;
}
