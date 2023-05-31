<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Rental;
use Doctrine\ORM\EntityManagerInterface;

class RentalRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function save(Rental $rental): void
    {
        $this->entityManager->persist($rental);
        $this->entityManager->flush();
    }
}
