<?php

namespace App\Enum;

enum RentalStatus: string
{
    case RESERVED = 'reserved';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
