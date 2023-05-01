<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class TransactionRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function save(Transaction $transaction): Transaction
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return $transaction;
    }

    public function findById(int $transactionId): ?Transaction
    {
        return $this->entityManager->find(Transaction::class, $transactionId);
    }
}
