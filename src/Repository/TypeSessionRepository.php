<?php

namespace App\Repository;

use App\Entity\TypeSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeSession[]    findAll()
 * @method TypeSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeSession::class);
    }

    // /**
    //  * @return TypeSession[] Returns an array of TypeSession objects
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
    public function findOneBySomeField($value): ?TypeSession
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
