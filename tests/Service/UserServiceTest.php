<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\UserRepository;
use App\Service\UserService;
use App\Tests\DatabaseDependantTestCase;
use Doctrine\ORM\EntityManagerInterface;

class UserServiceTest extends DatabaseDependantTestCase
{
    public function testItCreatesNewUserSuccessfully()
    {
        $userData = [
            'email' => 'foo@bar.com',
            'password' => 'foobar',
            'firstName' => 'Foo',
            'lastName' => 'Bar',
        ];

        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $userRepository = new UserRepository($entityManager);
        $userService = new UserService($userRepository);

        $user = $userService->createNewUser($userData);

        $this->assertNotNull($user->getId());
        $this->assertSame($userData['email'], $user->getEmail());
    }
}
