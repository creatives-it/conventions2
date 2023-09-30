<?php

namespace App\Repository;

use App\Entity\ConventionStade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConventionStade|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionStade|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionStade[]    findAll()
 * @method ConventionStade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionStadeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionStade::class);
    }

    // /**
    //  * @return ConventionStade[] Returns an array of ConventionStade objects
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
    public function findOneBySomeField($value): ?ConventionStade
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
