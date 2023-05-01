<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Wallet;
use App\Enum\TransactionStatus;
use App\Enum\TransactionType;
use App\Repository\WalletRepository;

final class WalletService
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
    ) {
    }

    public function createUserWallet(User $user): Wallet
    {
        if ($user->getWallet() !== null) {
            throw new \DomainException('User already has a wallet');
        }

        $wallet = new Wallet();
        $wallet->setUser($user);
        $wallet->setBalance('0.00');
        $wallet->setIsLocked(false);
        $wallet->setCreatedAt(new \DateTimeImmutable());
        $wallet->setUpdatedAt(new \DateTimeImmutable());

        $user->setWallet($wallet);

        return $this->walletRepository->save($wallet);
    }

    public function addFundsToWallet(Transaction $transaction, Wallet $wallet): void
    {
        if ($transaction->getType() !== TransactionType::DEPOSIT) {
            throw new \DomainException('Transaction type must be deposit');
        }

        if ($transaction->getStatus() !== TransactionStatus::SUCCESS) {
            throw new \DomainException('Transaction status must be successfull');
        }

        $previousBalance = (float) $wallet->getBalance();
        $newBalance = round($previousBalance + ((float) $transaction->getAmount()), 2);

        if ($previousBalance < 0 && $newBalance >= 0) {
            $wallet->setIsLocked(false);
        }

        $this->walletRepository->updateWalletBalance($wallet, (string) $newBalance);
    }
}
