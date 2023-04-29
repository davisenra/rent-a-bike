<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;

class WalletRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(User $user): Wallet
    {
        $wallet = new Wallet();
        $wallet->setUser($user);
        $wallet->setBalance('0.00');
        $wallet->setIsLocked(false);
        $wallet->setCreatedAt(new \DateTimeImmutable());
        $wallet->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        return $wallet;
    }
}
