<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class WalletRepository
{
    private readonly EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Wallet::class);
    }
}
