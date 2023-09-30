<?php

namespace App\Repository;

use App\Entity\ConventionSignature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConventionSignature|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionSignature|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionSignature[]    findAll()
 * @method ConventionSignature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionSignatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionSignature::class);
    }

    // /**
    //  * @return ConventionSignature[] Returns an array of ConventionSignature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConventionSignature
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
