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
    private readonly UserService $userService;

    protected function setUp(): void
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $userRepository = new UserRepository($entityManager);
        $this->userService = new UserService($userRepository);

        parent::setUp();
    }

    public function testItCreatesNewUserSuccessfully()
    {
        $user = $this->userService->createNewUser([
            'email' => 'foo@bar.com',
            'password' => '123456',
            'firstName' => 'Foo',
            'lastName' => 'Bar',
        ]);

        $this->assertNotNull($user->getId());
        $this->assertSame('foo@bar.com', $user->getEmail());
    }

    public function testItChecksIfUserExistsSuccessfully()
    {
        $this->userService->createNewUser([
            'email' => 'foo@bar.com',
            'password' => '123456',
            'firstName' => 'Foo',
            'lastName' => 'Bar',
        ]);

        $this->assertTrue($this->userService->checkIfUserExistsByEmail('foo@bar.com'));
    }

    public function testEmailIsUnique()
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $this->userService->createNewUser([
            'email' => 'foo@bar.com',
            'password' => '123456',
            'firstName' => 'Foo',
            'lastName' => 'Bar',
        ]);
        $this->userService->createNewUser([
            'email' => 'foo@bar.com',
            'password' => '123456',
            'firstName' => 'Foo',
            'lastName' => 'Bar',
        ]);
    }
}
