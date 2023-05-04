<?php

declare(strict_types=1);

namespace App\Request;

use App\Request\CustomRequest\CustomRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class WalletDepositRequest extends CustomRequest
{
    #[Assert\NotBlank()]
    #[Assert\GreaterThan(value: 5)]
    public string $amount;
}
