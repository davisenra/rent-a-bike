<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\Wallet;
use App\Enum\TransactionStatus;
use App\Enum\TransactionType;
use App\Repository\TransactionRepository;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository
    ) {}

    /**
     * @param array<string, mixed> $transactionData
     * @param Wallet $wallet
     * @return Transaction
     */
    public function createDepositTransaction(array $transactionData, Wallet $wallet): Transaction
    {
        $transaction = new Transaction();
        $transaction->setWallet($wallet);
        $transaction->setAmount($transactionData['amount']);
        $transaction->setType(TransactionType::DEPOSIT);
        $transaction->setStatus(TransactionStatus::SUCCESS);
        $transaction->setCreatedAt(new \DateTimeImmutable());
        $transaction->setUpdatedAt(new \DateTimeImmutable());

        return $this->transactionRepository->save($transaction);
    }
}