<?php

declare(strict_types=1);

namespace App\Request;

interface CustomRequestInterface
{
    public function validate(): bool;
}
