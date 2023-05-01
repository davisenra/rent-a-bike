<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\WalletService;
use App\Tests\Database\DatabaseDependantTestCase;
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
        $userEntity = $this->createUserEntity();
        $user = $this->userRepository->save($userEntity);

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->assertNotNull($wallet->getId());
        $this->assertSame($user->getId(), $wallet->getUser()->getId());
    }

    public function testUserCanHaveOnlyOneWallet()
    {
        $userEntity = $this->createUserEntity();
        $user = $this->userRepository->save($userEntity);

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $user->setWallet($wallet);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User already has a wallet');

        $walletService->createUserWallet($user);
    }

    public function testWalletHasDefaultBalanceOfZero()
    {
        $userEntity = $this->createUserEntity();
        $user = $this->userRepository->save($userEntity);

        $walletService = new WalletService($this->walletRepository);
        $wallet = $walletService->createUserWallet($user);

        $this->assertSame('0.00', $wallet->getBalance());
    }

    private function createUserEntity(): User
    {
        $userEntity = new User();
        $userEntity->setEmail($this->userData['email']);
        $userEntity->setPassword($this->userData['password']);
        $userEntity->setFirstName($this->userData['firstName']);
        $userEntity->setLastName($this->userData['lastName']);
        $userEntity->setCreatedAt(new \DateTimeImmutable());
        $userEntity->setUpdatedAt(new \DateTimeImmutable());

        return $userEntity;
    }
}