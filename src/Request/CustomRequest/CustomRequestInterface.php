<?php

declare(strict_types=1);

namespace App\Request\CustomRequest;

interface CustomRequestInterface
{
    public function validate(): bool;
}
