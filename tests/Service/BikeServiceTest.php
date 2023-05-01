<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Bike;
use App\Repository\BikeRepository;
use App\Service\BikeService;
use App\Tests\Database\DatabaseDependantTestCase;
use Doctrine\ORM\EntityManagerInterface;

class BikeServiceTest extends DatabaseDependantTestCase
{
    private readonly BikeRepository $bikeRepository;

    protected function setUp(): void
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $this->bikeRepository = new BikeRepository($entityManager);

        parent::setUp();
    }

    public function testItReturnsAllBikes()
    {
        $bike1 = new Bike();
        $bike1->setModel('Trek Madonne');
        $bike1->setPricePerMinute('1.50');
        $bike1->setIsAvailable(true);
        $bike1->setCreatedAt(new \DateTimeImmutable());
        $bike1->setUpdatedAt(new \DateTimeImmutable());

        $bike2 = new Bike();
        $bike2->setModel('Trek Emonda');
        $bike2->setPricePerMinute('1.50');
        $bike2->setIsAvailable(true);
        $bike2->setCreatedAt(new \DateTimeImmutable());
        $bike2->setUpdatedAt(new \DateTimeImmutable());

        $this->bikeRepository->save($bike1);
        $this->bikeRepository->save($bike2);

        $bikeService = new BikeService($this->bikeRepository);

        $allBikes = $bikeService->getAllBikes();

        $this->assertCount(2, $allBikes);
    }

    public function testItOnlyReturnsAvailableBikes()
    {
        $bike1 = new Bike();
        $bike1->setModel('Trek Madonne');
        $bike1->setPricePerMinute('1.50');
        $bike1->setIsAvailable(true);
        $bike1->setCreatedAt(new \DateTimeImmutable());
        $bike1->setUpdatedAt(new \DateTimeImmutable());

        $bike2 = new Bike();
        $bike2->setModel('Trek Emonda');
        $bike2->setPricePerMinute('1.50');
        $bike2->setIsAvailable(false);
        $bike2->setCreatedAt(new \DateTimeImmutable());
        $bike2->setUpdatedAt(new \DateTimeImmutable());

        $this->bikeRepository->save($bike1);
        $this->bikeRepository->save($bike2);

        $bikeService = new BikeService($this->bikeRepository);

        $allBikes = $bikeService->getAvailableBikes();

        $this->assertCount(1, $allBikes);
    }
}
