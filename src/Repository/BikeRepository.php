<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bike;
use Doctrine\ORM\EntityManagerInterface;

class BikeRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(Bike $bike): Bike
    {
        $this->entityManager->persist($bike);
        $this->entityManager->flush();

        return $bike;
    }

    /**
     * @return Bike[]
     */
    public function allAvailableBikes(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Bike::class, 'b')
            ->where('b.isAvailable = true')
            ->getQuery()
            ->getResult();
    }
}
