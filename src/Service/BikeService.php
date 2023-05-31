<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Bike;
use App\Repository\BikeRepository;

class BikeService
{
    public function __construct(
        private readonly BikeRepository $bikeRepository
    ) {
    }

    public function getOneById(int $bikeId): ?Bike
    {
        return $this->bikeRepository->find($bikeId);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAllBikes(): array
    {
        return array_map(function (Bike $bike) {
            return [
                'id' => $bike->getId(),
                'model' => $bike->getModel(),
                'pricePerMinute' => $bike->getPricePerMinute(),
                'isAvailable' => $bike->isAvailable(),
            ];
        }, $this->bikeRepository->findAll());
    }

    /**
     * @return array<string, mixed>
     */
    public function getAvailableBikes(): array
    {
        return array_map(function (Bike $bike) {
            return [
                'id' => $bike->getId(),
                'model' => $bike->getModel(),
                'pricePerMinute' => $bike->getPricePerMinute(),
            ];
        }, $this->bikeRepository->allAvailableBikes());
    }
}
