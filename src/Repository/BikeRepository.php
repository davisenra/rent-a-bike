<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bike;
use Doctrine\ORM\EntityManagerInterface;

class BikeRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function find(int $bikeId): ?Bike
    {
        return $this->entityManager->find(Bike::class, $bikeId);
    }

    public function update(Bike $bike): void
    {
        $this->entityManager->flush();
    }

    public function save(Bike $bike): Bike
    {
        $this->entityManager->persist($bike);
        $this->entityManager->flush();

        return $bike;
    }

    /**
     * @return Bike[]
     */
    public function findAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Bike::class, 'b')
            ->getQuery()
            ->getResult();
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
