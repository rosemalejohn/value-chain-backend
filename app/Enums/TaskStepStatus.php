<?php

namespace App\Enums;

enum TaskStepStatus: int
{
    case Pending = 1;
    case Ready = 2;
    case Testing = 3;
    case Fixing = 4;
    case Finished = 5;
}
