<?php

namespace App\Repository\Hauling;

use App\Entity\Hauling\Hauling;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Hauling>
 */
class HaulingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hauling::class);
    }

    public function findByUser(User|UserInterface|null $user): ?Hauling
    {
        return $this->createQueryBuilder('h')
                    ->andWhere('h.user = :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }

    public function findByAnonymousUser(string $anonymousUser): ?Hauling
    {
        return $this->createQueryBuilder('h')
                    ->andWhere('h.anonymous_user = :anonymousUser')
                    ->setParameter('anonymousUser', $anonymousUser)
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }

    //    /**
    //     * @return Hauling[] Returns an array of Hauling objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Hauling
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
