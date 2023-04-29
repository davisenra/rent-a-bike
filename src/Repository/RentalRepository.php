<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Rental;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RentalRepository
{
    private readonly EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Rental::class);
    }
}
