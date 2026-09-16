<?php

namespace App\Enums;

enum MovementType: string
{
    case In = 'in';
    case Out = 'out';
    case Adjust = 'adjustment';
    case Initial = 'initial';
}
