<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InsufficientFundsException extends \JsonException
{
    protected $message;
    protected $code;

    public function __construct(
        string $message = "Insufficient funds",
        int $code = Response::HTTP_CONFLICT,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}