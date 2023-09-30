<?php

namespace App\Repository;

use App\Entity\DomaineCompetence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DomaineCompetence|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomaineCompetence|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomaineCompetence[]    findAll()
 * @method DomaineCompetence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomaineCompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomaineCompetence::class);
    }

    // /**
    //  * @return DomaineCompetence[] Returns an array of DomaineCompetence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DomaineCompetence
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
