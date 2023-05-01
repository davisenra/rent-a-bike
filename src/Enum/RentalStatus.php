<?php

namespace App\Enum;

enum RentalStatus: string
{
    case RESERVED = 'reserved';
    case CANCELLED = 'cancelled';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
}
