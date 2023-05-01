<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Transaction;
use App\Entity\User;
use App\Enum\TransactionStatus;
use App\Enum\TransactionType;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\WalletService;
use App\Tests\Database\DatabaseDependantTestCase;
use Doctrine\ORM\EntityManagerInterface;

class WalletServiceTest extends DatabaseDependantTestCase
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

    public function testUserCanCreateWalletSuccessfully()
    {
        $user = $this->createUserEntity();

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->assertNotNull($wallet->getId());
        $this->assertSame($user->getId(), $wallet->getUser()->getId());
    }

    public function testUserCanHaveOnlyOneWallet()
    {
        $user = $this->createUserEntity();

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $user->setWallet($wallet);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User already has a wallet');

        $walletService->createUserWallet($user);
    }

    public function testWalletHasDefaultBalanceOfZero()
    {
        $user = $this->createUserEntity();

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->assertSame('0.00', $wallet->getBalance());
    }

    public function testItAddsFundsToWalletCorrectly()
    {
        $user = $this->createUserEntity();

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->assertEquals('0.00', $wallet->getBalance());

        $transaction = new Transaction();
        $transaction->setWallet($wallet);
        $transaction->setAmount('10.00');
        $transaction->setType(TransactionType::DEPOSIT);
        $transaction->setStatus(TransactionStatus::SUCCESS);
        $transaction->setCreatedAt(new \DateTimeImmutable());
        $transaction->setUpdatedAt(new \DateTimeImmutable());

        $this->transactionRepository->save($transaction);

        $walletService = new WalletService($this->walletRepository);
        $walletService->addFundsToWallet($transaction, $wallet);

        $this->assertEquals('10', $wallet->getBalance());
    }

    public function testItThrowsErrorIfTransactionIsNotSuccessfull()
    {
        $user = $this->createUserEntity();

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $transaction = new Transaction();
        $transaction->setWallet($wallet);
        $transaction->setAmount('10.00');
        $transaction->setType(TransactionType::DEPOSIT);
        $transaction->setStatus(TransactionStatus::PENDING);
        $transaction->setCreatedAt(new \DateTimeImmutable());
        $transaction->setUpdatedAt(new \DateTimeImmutable());

        $this->transactionRepository->save($transaction);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Transaction status must be successfull');

        $walletService = new WalletService($this->walletRepository);
        $walletService->addFundsToWallet($transaction, $wallet);

        $this->assertEquals('0.00', $wallet->getBalance());
    }

    public function testItUnlocksTheAccountAfterBalanceIsNotNegative()
    {
        $user = $this->createUserEntity();

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->walletRepository->updateWalletBalance($wallet, '-10.00');

        $this->assertEquals('-10.00', $wallet->getBalance());

        $this->walletRepository->lockWallet($wallet);

        $this->assertTrue($wallet->isLocked());

        $transaction = new Transaction();
        $transaction->setWallet($wallet);
        $transaction->setAmount('10.00');
        $transaction->setType(TransactionType::DEPOSIT);
        $transaction->setStatus(TransactionStatus::SUCCESS);
        $transaction->setCreatedAt(new \DateTimeImmutable());
        $transaction->setUpdatedAt(new \DateTimeImmutable());

        $this->transactionRepository->save($transaction);

        $walletService->addFundsToWallet($transaction, $wallet);

        $this->assertFalse($wallet->isLocked());
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
