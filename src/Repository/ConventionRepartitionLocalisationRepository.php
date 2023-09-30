<?php

namespace App\Repository;

use App\Entity\ConventionRepartitionLocalisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConventionRepartitionLocalisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionRepartitionLocalisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionRepartitionLocalisation[]    findAll()
 * @method ConventionRepartitionLocalisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionRepartitionLocalisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionRepartitionLocalisation::class);
    }

    // /**
    //  * @return ConventionRepartitionLocalisation[] Returns an array of ConventionRepartitionLocalisation objects
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
    public function findOneBySomeField($value): ?ConventionRepartitionLocalisation
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
