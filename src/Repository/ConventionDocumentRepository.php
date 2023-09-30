<?php

namespace App\Repository;

use App\Entity\ConventionDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConventionDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionDocument[]    findAll()
 * @method ConventionDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionDocument::class);
    }

    // /**
    //  * @return ConventionDocument[] Returns an array of ConventionDocument objects
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
    public function findOneBySomeField($value): ?ConventionDocument
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
