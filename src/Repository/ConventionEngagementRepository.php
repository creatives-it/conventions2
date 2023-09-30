<?php

namespace App\Repository;

use App\Entity\ConventionEngagement;
use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Convention;

/**
 * @method ConventionEngagement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionEngagement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionEngagement[]    findAll()
 * @method ConventionEngagement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionEngagementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionEngagement::class);
    }

    public function getMontantEngagementsByAnnee(Convention $convention)
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('c.annee as x','SUM(c.montantProgramme) as y')
            ->where($qb0->expr()->isNotNull('c.annee'))
            ->andWhere('c.convention = :i')->setParameter('i', $convention)
            ->groupBy('x')
            ->orderBy('x', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getMontantEngagementsPartenaireByAnnee(Convention $convention, Partenaire $partenaire)
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('c.annee as x','SUM(c.montantProgramme) as y')
            ->where($qb0->expr()->isNotNull('c.annee'))
            ->andWhere('c.convention = :i')->setParameter('i', $convention)
            ->andWhere('c.partenaire = :ip')->setParameter('ip', $partenaire)
            ->groupBy('x')
            ->orderBy('x', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return ConventionEngagement[] Returns an array of ConventionEngagement objects
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
    public function findOneBySomeField($value): ?ConventionEngagement
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
