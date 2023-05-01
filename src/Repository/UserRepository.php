<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function save(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function findOneById(int $userId): ?User
    {
        return $this->entityManager->find(User::class, $userId);
    }

    public function findOneByEmail(string $email): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
