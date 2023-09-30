<?php

namespace App\Repository;

use App\Entity\Convention;
use App\Entity\ConventionContribution;
use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConventionContribution|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConventionContribution|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConventionContribution[]    findAll()
 * @method ConventionContribution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionContributionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConventionContribution::class);
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

    public function getMontantContributionsByAnnee(Convention $convention)
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('c.annee as x','SUM(c.montant) as y')
            ->where($qb0->expr()->isNotNull('c.annee'))
            ->andWhere('c.convention = :i')->setParameter('i', $convention)
            ->groupBy('x')
            ->orderBy('x', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getMontantContributionsPartenaireByAnnee(Convention $convention, Partenaire $partenaire)
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('c.annee as x','SUM(c.montant) as y')
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
    //  * @return ConventionContribution[] Returns an array of ConventionContribution objects
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
    public function findOneBySomeField($value): ?ConventionContribution
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
