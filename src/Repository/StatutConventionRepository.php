<?php

namespace App\Repository;

use App\Entity\StatutConvention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutConvention|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutConvention|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutConvention[]    findAll()
 * @method StatutConvention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutConventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutConvention::class);
    }

    // /**
    //  * @return StatutConvention[] Returns an array of StatutConvention objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatutConvention
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
