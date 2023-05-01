<?php

namespace App\Enum;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case RENTAL = 'rental';
}
