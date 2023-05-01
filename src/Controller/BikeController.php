<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BikeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BikeController extends AbstractController
{
    public function __construct(
        private readonly BikeService $bikeService
    ) {
    }

    #[Route('/bikes', name: 'all_bikes')]
    public function all(): JsonResponse
    {
        $bikes = $this->bikeService->getAllBikes();

        return $this->json([
            'count' => count($bikes),
            'data' => $bikes,
        ], Response::HTTP_OK);
    }

    #[Route('/bikes/available', name: 'available_bikes')]
    public function availableBikes(): JsonResponse
    {
        $availableBikes = $this->bikeService->getAvailableBikes();

        return $this->json([
            'count' => count($availableBikes),
            'data' => $availableBikes,
        ], Response::HTTP_OK);
    }
}
