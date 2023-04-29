<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

final class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * @param array<string, mixed> $userData
     * @return User
     */
    public function createNewUser(array $userData): User
    {
        return $this->userRepository->save($userData);
    }
}