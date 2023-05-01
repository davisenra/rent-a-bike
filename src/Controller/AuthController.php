<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\RegisterUserRequest;
use App\Service\UserService;
use App\Service\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
final class AuthController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly WalletService $walletService,
    ) {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(RegisterUserRequest $registerUserRequest): JsonResponse
    {
        if (!$registerUserRequest->validate()) {
            return $this->json($registerUserRequest->getValidationErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($this->userService->checkIfUserExistsByEmail($registerUserRequest->email)) {
            throw new \JsonException('Email already registered', Response::HTTP_CONFLICT);
        }

        $user = $this->userService->createNewUser((array) $registerUserRequest);
        $this->walletService->createUserWallet($user);

        return $this->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }
}
