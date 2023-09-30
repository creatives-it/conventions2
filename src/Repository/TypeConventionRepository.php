<?php

namespace App\Repository;

use App\Entity\TypeConvention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeConvention|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeConvention|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeConvention[]    findAll()
 * @method TypeConvention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeConventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeConvention::class);
    }

    // /**
    //  * @return TypeConvention[] Returns an array of TypeConvention objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeConvention
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
