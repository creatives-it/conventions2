<?php

namespace App\Repository;

use App\Entity\TypeEntite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeEntite|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEntite|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEntite[]    findAll()
 * @method TypeEntite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEntiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEntite::class);
    }

    // /**
    //  * @return TypeEntite[] Returns an array of TypeEntite objects
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
    public function findOneBySomeField($value): ?TypeEntite
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
