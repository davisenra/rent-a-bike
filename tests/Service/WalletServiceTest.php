<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\WalletService;
use App\Tests\DatabaseDependantTestCase;
use Doctrine\ORM\EntityManagerInterface;

class WalletServiceTest extends DatabaseDependantTestCase
{
    private array $userData = [
        'email' => 'foo@bar.com',
        'password' => 'foobar',
        'firstName' => 'Foo',
        'lastName' => 'Bar',
    ];

    private UserRepository $userRepository;
    private WalletRepository $walletRepository;

    public function setUp(): void
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $this->userRepository = new UserRepository($entityManager);
        $this->walletRepository = new WalletRepository($entityManager);

        parent::setUp();
    }

    public function testUserCanCreateWalletSuccessfully()
    {
        $user = $this->userRepository->save($this->userData);

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->assertNotNull($wallet->getId());
        $this->assertSame($user->getId(), $wallet->getUser()->getId());
    }

    public function testUserCanHaveOnlyOneWallet()
    {
        $user = $this->userRepository->save($this->userData);

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $user->setWallet($wallet);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User already has a wallet');

        $walletService->createUserWallet($user);
    }

    public function testWalletHasDefaultBalanceOfZero()
    {
        $user = $this->userRepository->save($this->userData);

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->assertSame('0.00', $wallet->getBalance());
    }
}