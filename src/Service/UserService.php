<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Rental;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @param array<string, mixed> $userData
     * @return User
     */
    public function createNewUser(array $userData): User
    {
        $password = password_hash($userData['password'], PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);

        $user = new User();
        $user->setEmail($userData['email']);
        $user->setPassword($password);
        $user->setFirstName($userData['firstName']);
        $user->setLastName($userData['lastName']);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $this->userRepository->save($user);
    }

    public function checkIfUserExistsByEmail(string $email): bool
    {
        return $this->userRepository->findOneByEmail($email) !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getUserProfileById(int $userId): array
    {
        $user = $this->userRepository->findOneById($userId);

        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'memberSince' => $user->getCreatedAt()->format('Y-m-d H:i:s'),

            'wallet' => [
                'balance' => $user->getWallet()->getBalance(),
                'isLocked' => $user->getWallet()->isLocked(),
            ],

            'rentals' => array_map(fn (Rental $rental) => [
                'status' => $rental->getStatus(),
                'totalPrice' => $rental->getTotalPrice(),
                'startTime' => $rental->getStartTime()->format('Y-m-d H:i:s'),
                'endTime' => $rental->getEndTime()->format('Y-m-d H:i:s'),
            ], $user->getRentals()->toArray()),

            'transactions' => array_map(fn (Transaction $transaction) => [
                'amount' => $transaction->getAmount(),
                'type' => $transaction->getType(),
                'date' => $transaction->getCreatedAt()->format('Y-m-d H:i:s'),
            ], $user->getWallet()->getTransactions()->toArray()),
        ];
    }
}
