<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
final class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {}

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function profile(): JsonResponse
    {
        $user = $this->getUser();
        $userProfile = $this->userService->getUserProfileById($user->getId());

        return $this->json($userProfile, Response::HTTP_OK);
    }
}