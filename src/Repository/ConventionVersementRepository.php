<?php

namespace App\Repository;

use App\Entity\ConventionVersement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConventionVersement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionVersement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionVersement[]    findAll()
 * @method ConventionVersement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionVersementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionVersement::class);
    }

    public function montantTotal()
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.montant) as montant')
            //->andWhere('c.anneeRegistre = :val')->setParameter('val', $annee)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    // /**
    //  * @return ConventionVersement[] Returns an array of ConventionVersement objects
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
    public function findOneBySomeField($value): ?ConventionVersement
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
