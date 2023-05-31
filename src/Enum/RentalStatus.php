<?php

namespace App\Enum;

enum RentalStatus: string
{
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
