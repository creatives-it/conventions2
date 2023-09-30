<?php

namespace App\Repository;

use App\Entity\DocumentPlanification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentPlanification|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentPlanification|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentPlanification[]    findAll()
 * @method DocumentPlanification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentPlanificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentPlanification::class);
    }

    // /**
    //  * @return DocumentPlanification[] Returns an array of DocumentPlanification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentPlanification
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
