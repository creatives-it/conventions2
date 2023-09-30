<?php

namespace App\Repository;

use App\Entity\ProjetPhase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjetPhase|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjetPhase|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjetPhase[]    findAll()
 * @method ProjetPhase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetPhaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjetPhase::class);
    }

    // /**
    //  * @return ProjetPhase[] Returns an array of ProjetPhase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProjetPhase
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
