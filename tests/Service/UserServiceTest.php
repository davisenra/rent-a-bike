<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\UserRepository;
use App\Service\UserService;
use App\Tests\Database\DatabaseDependantTestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class UserServiceTest extends DatabaseDependantTestCase
{
    private readonly UserRepository $userRepository;
    private readonly UserService $userService;

    protected function setUp(): void
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $this->userRepository = new UserRepository($entityManager);
        $this->userService = new UserService($this->userRepository);

        parent::setUp();
    }

    private array $userData = [
        'email' => 'foo@bar.com',
        'password' => 'foobar',
        'firstName' => 'Foo',
        'lastName' => 'Bar',
    ];

    public function testItCreatesNewUserSuccessfully()
    {
        $user = $this->userService->createNewUser($this->userData);

        $this->assertNotNull($user->getId());
        $this->assertSame($this->userData['email'], $user->getEmail());
    }

    public function testItChecksIfUserExistsSuccessfully()
    {
        $this->userService->createNewUser($this->userData);

        $this->assertTrue($this->userService->checkIfUserExistsByEmail($this->userData['email']));
    }

    public function testEmailIsUnique()
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $this->userService->createNewUser($this->userData);
        $this->userService->createNewUser($this->userData);
    }
}
