<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @param array<string, string> $data
     * @return User
     */
    public function save(array $data): User
    {
        $password = password_hash($data['password'], PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);

        $userEntity = new User();
        $userEntity->setEmail($data['email']);
        $userEntity->setPassword($password);
        $userEntity->setFirstName($data['firstName']);
        $userEntity->setLastName($data['lastName']);
        $userEntity->setCreatedAt(new \DateTimeImmutable());
        $userEntity->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        return $userEntity;
    }
}
