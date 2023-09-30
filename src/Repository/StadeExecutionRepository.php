<?php

namespace App\Repository;

use App\Entity\StadeExecution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StadeExecution|null find($id, $lockMode = null, $lockVersion = null)
 * @method StadeExecution|null findOneBy(array $criteria, array $orderBy = null)
 * @method StadeExecution[]    findAll()
 * @method StadeExecution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StadeExecutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StadeExecution::class);
    }

    // /**
    //  * @return StadeExecution[] Returns an array of StadeExecution objects
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
    public function findOneBySomeField($value): ?StadeExecution
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
