<?php

declare(strict_types=1);

namespace App\Request;

use App\Request\CustomRequest\CustomRequest;
use Symfony\Component\Validator\Constraints as Assert;

class NewRentalRequest extends CustomRequest
{
    #[Assert\NotBlank()]
    public int $bikeId;
}