<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Bike;
use App\Entity\Rental;
use App\Entity\User;
use App\Enum\RentalStatus;
use App\Exception\InsufficientFundsException;
use App\Repository\BikeRepository;
use App\Repository\RentalRepository;

class RentalService
{
    public function __construct(
        private readonly RentalRepository $rentalRepository,
        private readonly BikeRepository $bikeRepository,
    ) {
    }

    public function createNewRental(User $user, Bike $bike): Rental
    {
        if (!$bike->isAvailable()) {
            throw new \DomainException('Requested bike is not available');
        }

        $userDoesNotHaveEnoughCredits = floatval($user->getWallet()->getBalance()) <= 0;

        if ($userDoesNotHaveEnoughCredits) {
            throw new InsufficientFundsException();
        }

        $bike->setIsAvailable(false);

        $rental = new Rental();
        $rental->setUser($user);
        $rental->setBike($bike);
        $rental->setStatus(RentalStatus::ONGOING);
        $rental->setStartTime(new \DateTimeImmutable());
        $rental->setCreatedAt(new \DateTimeImmutable());
        $rental->setUpdatedAt(new \DateTimeImmutable());

        $this->rentalRepository->save($rental);

        return $rental;
    }
}