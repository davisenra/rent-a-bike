<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\WalletRepository;

final class WalletService
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
    ) {}

    public function createUserWallet(User $user): Wallet
    {
        if ($user->getWallet() !== null) {
            throw new \DomainException('User already has a wallet');
        }

        return $this->walletRepository->save($user);
    }
}