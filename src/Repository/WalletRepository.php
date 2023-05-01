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

    public function save(Wallet $wallet): Wallet
    {
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        return $wallet;
    }

    public function updateWalletBalance(Wallet $wallet, string $newBalance): Wallet
    {
        $wallet->setBalance($newBalance);
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        return $wallet;
    }

    public function lockWallet(Wallet $wallet): Wallet
    {
        $wallet->setIsLocked(true);
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        return $wallet;
    }
}
