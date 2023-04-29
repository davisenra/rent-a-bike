<?php

namespace App\Enum;

enum TransactionStatus
{
    case PENDING;
    case SUCCESS;
    case FAILED;
}
