<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\TransactionService;
use App\Tests\Database\DatabaseDependantTestCase;
use Doctrine\ORM\EntityManagerInterface;

class TransactionServiceTest extends DatabaseDependantTestCase
{
    private UserRepository $userRepository;
    private WalletRepository $walletRepository;
    private TransactionRepository $transactionRepository;

    public function setUp(): void
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $this->userRepository = new UserRepository($entityManager);
        $this->walletRepository = new WalletRepository($entityManager);
        $this->transactionRepository = new TransactionRepository($entityManager);

        parent::setUp();
    }

    public function testTransacationCanBeCreated()
    {
        $user = $this->createUserEntity();

        $walletEntity = new Wallet();
        $walletEntity->setUser($user);
        $walletEntity->setIsLocked(false);
        $walletEntity->setBalance('0.00');
        $walletEntity->setCreatedAt(new \DateTimeImmutable());
        $walletEntity->setUpdatedAt(new \DateTimeImmutable());

        $wallet = $this->walletRepository->save($walletEntity);

        $transactionService = new TransactionService($this->transactionRepository);
        $transaction = $transactionService->createDepositTransaction(['amount' => '100.00'], $wallet);

        $this->assertSame($transaction, $this->transactionRepository->findById($transaction->getId()));
        $this->assertSame($transaction->getAmount(), '100.00');
    }

    private function createUserEntity(): User
    {
        $userEntity = new User();
        $userEntity->setEmail('foo@bar.com');
        $userEntity->setPassword('123456');
        $userEntity->setFirstName('Foo');
        $userEntity->setLastName('Bar');
        $userEntity->setCreatedAt(new \DateTimeImmutable());
        $userEntity->setUpdatedAt(new \DateTimeImmutable());

        return $this->userRepository->save($userEntity);
    }
}
