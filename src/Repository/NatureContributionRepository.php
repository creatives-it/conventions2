<?php

namespace App\Repository;

use App\Entity\NatureContribution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NatureContribution|null find($id, $lockMode = null, $lockVersion = null)
 * @method NatureContribution|null findOneBy(array $criteria, array $orderBy = null)
 * @method NatureContribution[]    findAll()
 * @method NatureContribution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NatureContributionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NatureContribution::class);
    }

    // /**
    //  * @return NatureContribution[] Returns an array of NatureContribution objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NatureContribution
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
