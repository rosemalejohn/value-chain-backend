<?php

namespace App\Enums;

enum TaskStepStatus: int
{
    case Pending = 1;
    case Ready = 2;
}
