<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\InsufficientFundsException;
use App\Request\NewRentalRequest;
use App\Service\BikeService;
use App\Service\RentalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class RentalController extends AbstractController
{
    public function __construct(
        private readonly RentalService $rentalService,
        private readonly BikeService $bikeService
    ) {
    }

    #[Route('/rentals', name: 'new-rental', methods: ['POST'])]
    public function newRental(NewRentalRequest $rentalRequest): JsonResponse
    {
        if (!$rentalRequest->validate()) {
            return $this->json($rentalRequest->getValidationErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var User $user */
        $user = $this->getUser();
        $requestedBike = $this->bikeService->getOneById($rentalRequest->bikeId);

        if ($requestedBike === null) {
            return $this->json([
                'message' => 'The requested bike was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$requestedBike->isAvailable()) {
            return $this->json([
                'message' => 'The requested bike is not available'
            ], Response::HTTP_NOT_FOUND);
        }

        $rental = $this->rentalService->createNewRental($user, $requestedBike);

        return $this->json([
            'rentalId' => $rental->getId(),
            'status' => $rental->getStatus(),
            'totalPrice' => $rental->getTotalPrice(),
            'startedAt' => $rental->getStartTime()->format('Y-m-d H:i:s'),
            'endedAt' => $rental->getEndTime()?->format('Y-m-d H:i:s'),
        ], Response::HTTP_CREATED);
    }
}