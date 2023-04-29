<?php

namespace App\Enum;

enum RentalStatus
{
    case RESERVED;
    case CANCELLED;
    case ONGOING;
    case COMPLETED;
}
