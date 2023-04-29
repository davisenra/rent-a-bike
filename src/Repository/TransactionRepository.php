<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

class TransactionRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}
}
