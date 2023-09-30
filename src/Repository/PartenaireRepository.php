<?php

namespace App\Repository;

use App\Entity\Convention;
use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Partenaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partenaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partenaire[]    findAll()
 * @method Partenaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartenaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partenaire::class);
    }

    public function getParticipationAnnuellesConvention(Convention $convention,Partenaire $partenaire)
    {
        $engagements = $this->createQueryBuilder('p')
            ->select('SUM(ce.montantProgramme) as montant, ce.annee as annee')
            ->join('p.conventionEngagements', 'ce')
            ->join('ce.convention', 'c')
            ->andWhere('c = :id')->setParameter('id', $convention)
            ->andWhere('p = :idp')->setParameter('idp', $partenaire)
            ->groupBy('annee')
            ->orderBy('annee')
            ->getQuery()
            ->getResult()
        ;
        $contributions = $this->createQueryBuilder('p')
            ->select('SUM(cc.montant) as montant, cc.annee as annee')
            ->join('p.conventionContributions', 'cc')
            ->join('cc.convention', 'c')
            ->andWhere('c = :id')->setParameter('id', $convention)
            ->andWhere('p = :idp')->setParameter('idp', $partenaire)
            ->groupBy('annee')
            ->orderBy('annee')
            ->getQuery()
            ->getResult()
        ;
        $participations = [];
        foreach ($engagements as $engagement):
            $participations[$engagement['annee']]['engagement'] = $engagement['montant'];
            $participations[$engagement['annee']]['contribution'] = 0;
        endforeach;
        foreach ($contributions as $contribution):
            if (!isset($participations[$contribution['annee']]['engagement'])) $participations[$contribution['annee']]['engagement'] = 0;
            $participations[$contribution['annee']]['contribution'] = $contribution['montant'];
        endforeach;
        ksort($participations);
        return $participations;
    }


    public function getParticipationAnnuelles(Partenaire $partenaire)
    {
        $engagements = $this->createQueryBuilder('p')
            ->select('SUM(ce.montantProgramme) as montant, ce.annee as annee')
            ->join('p.conventionEngagements', 'ce')
            ->andWhere('p = :id')->setParameter('id', $partenaire)
            ->groupBy('annee')
            ->orderBy('annee')
            ->getQuery()
            ->getResult()
            ;
        $contributions = $this->createQueryBuilder('p')
            ->select('SUM(cc.montant) as montant, cc.annee as annee')
            ->join('p.conventionContributions', 'cc')
            ->andWhere('p = :id')->setParameter('id', $partenaire)
            ->groupBy('annee')
            ->orderBy('annee')
            ->getQuery()
            ->getResult()
        ;
        $participations = [];
        foreach ($engagements as $engagement):
            $participations[$engagement['annee']]['engagement'] = $engagement['montant'];
            $participations[$engagement['annee']]['contribution'] = 0;
        endforeach;
        foreach ($contributions as $contribution):
            $participations[$contribution['annee']]['contribution'] = $contribution['montant'];
        endforeach;

        return $participations;
    }

    public function nbreTotal()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as nbre')
            //->andWhere('p.anneeRegistre = :val')->setParameter('val', $annee)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    // /**
    //  * @return Partenaire[] Returns an array of Partenaire objects
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
    public function findOneBySomeField($value): ?Partenaire
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
