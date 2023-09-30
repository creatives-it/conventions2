<?php

namespace App\Repository;

use App\Entity\ConventionEngagementRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConventionEngagementRegion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionEngagementRegion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionEngagementRegion[]    findAll()
 * @method ConventionEngagementRegion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionEngagementRegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionEngagementRegion::class);
    }

    // /**
    //  * @return ConventionEngagementRegion[] Returns an array of ConventionEngagementRegion objects
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
    public function findOneBySomeField($value): ?ConventionEngagementRegion
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
