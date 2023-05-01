<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

class RentalRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }
}
