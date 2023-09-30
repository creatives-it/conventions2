<?php

namespace App\Repository;

use App\Entity\Convention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Convention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Convention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Convention[]    findAll()
 * @method Convention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Convention::class);
    }


    public function getDeletedItems()
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->where($qb0->expr()->isNotNull('c.deletedAt'))
            ->orderBy('c.deletedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function nbreTotal()
    {
        try {
            return $this->createQueryBuilder('c')
                ->select('COUNT(c.id) as nbre')
                //->andWhere('c.anneeRegistre = :val')->setParameter('val', $annee)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }


    public function nbreConventionsApprouves($typeConvention = 0)
    {
        $qb0 = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as y')
            ->leftJoin('c.stade','s')
            ->leftJoin('c.typeConvention','t')
            ->andWhere('s.id = 4')
            ->andWhere('c.isAvenant = 0');
        if ($typeConvention > 0) {
            $qb0->andWhere('t.id = :tc')->setParameter('tc',$typeConvention);
        }
        try {
            return $qb0->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) { return 0;
        } catch (NonUniqueResultException $e) { return 0;
        }

    }

    public function nbreAvenantsApprouves()
    {
        $qb0 = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as y')
            ->leftJoin('c.stade','s')
            //->leftJoin('c.typeConvention','t')->andWhere('t.id = 3')
            ->andWhere('s.id = 4')
            ->andWhere('c.isAvenant = 1');

        try {
            return $qb0->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) { return 0;
        } catch (NonUniqueResultException $e) { return 0;
        }

    }

    public function nbreConventionsByStade($stade)
    {
        $qb0 = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as y')
            ->leftJoin('c.stade','s')
            //->leftJoin('c.typeConvention','t')->andWhere('t.id = 3')
            ->andWhere('s.id IN (:ts)')->setParameter('ts',$stade)
            //->andWhere('c.isAvenant = 1')
        ;

        try {
            return $qb0->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) { return 0;
        } catch (NonUniqueResultException $e) { return 0;
        }

    }


    public function getLastInserteds($max=5)
    {
        return $this->createQueryBuilder('c')
            ->select('c.numero as x, c.objetConvention as y, c.id as id')
            //->leftJoin('c.entiteEmettrice','e')
            //->andWhere('c.anneeRegistre = :val')->setParameter('val', $annee)
            ->setMaxResults($max)
            ->orderBy('c.id','DESC')
            ->getQuery()->getResult()
            ;
    }

    public function getLastUpdateds($max=5)
    {
        return $this->createQueryBuilder('c')
            ->select('c.numero as x, c.objetConvention as y, c.id as id')
            //->leftJoin('c.entiteEmettrice','e')
            //->andWhere('c.anneeRegistre = :val')->setParameter('val', $annee)
            ->setMaxResults($max)
            ->orderBy('c.updatedAt','DESC')
            ->getQuery()->getResult()
            ;
    }


    public function getNbreByAnnee()
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('YEAR(s.date) as x','COUNT(c.id) as y')
            ->join('c.sessionApprobation', 's')
            ->where($qb0->expr()->isNotNull('s.date'))
            ->andWhere('c.isAvenant = 0')
            ->groupBy('x')
            ->orderBy('x', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNbreByBranche()
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('b.name as x','COUNT(c.id) as y')
            ->leftJoin('c.secteur','s')
            ->leftJoin('s.branche', 'b')
            ->where($qb0->expr()->isNotNull('s.branche'))
            ->andWhere('c.isAvenant = 0')
            ->groupBy('x')
            ->orderBy('x', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNbreByStade()
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('s.name as x','COUNT(c.id) as y')
            ->leftJoin('c.stade','s')
            ->where($qb0->expr()->isNotNull('c.stade'))
            ->andWhere('c.isAvenant = 0')
            ->groupBy('x')
            ->orderBy('x', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNbreBySecteur()
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('s.name as x','COUNT(c.id) as y')
            ->leftJoin('c.secteur','s')
            ->where($qb0->expr()->isNotNull('c.secteur'))
            ->andWhere('c.isAvenant = 0')
            ->groupBy('x')
            ->orderBy('y', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNbreByLocalisation()
    {
        $qb0 = $this->createQueryBuilder('c');
        return $qb0
            ->select('l.name as x','COUNT(c.id) as y', 'l.id')
            ->leftJoin('c.localisation','l')
            ->where($qb0->expr()->isNotNull('c.localisation'))
            ->andWhere('c.isAvenant = 0')
            ->groupBy('x','l.id')
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }/**/

     /**
      * @return Convention[] Returns an array of Convention objects
      */
    public function getListAttenteSignaturePartenaires($delaiMin = 15)
    {
        $return = [];
        $entities = $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')->setParameter('val', $value)
            ->orderBy('c.numero', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        $i = 0;
        foreach ($entities as $entity):
            if (!$entity->getEstSigneeParPartenaires()) {

                /** @var $entity Convention */
                foreach ($entity->getConventionSignatures() as $subEntity):
                    if ($subEntity->getDelaiAttenteSignature() > $delaiMin) {
                        $return[$i]['entity'] = $entity;
                        $return[$i]["delaiConsomme"] = $entity->getDelaiMoyenConsommePourSignaturePartenaires();
                    }
                endforeach;
                $i++;
            }
        endforeach;
        //dd($i);
        //dd($order);
        //dump($return);
        $order = "delaiConsomme";
        usort($return, function($a, $b) use ($order) {
            return ($b[$order] < $a[$order]) ? -1 : 1;
        });
        $return2 = [];
        foreach( $return as $r):
            $return2[] = $r['entity'];
        endforeach;

        //dd($return2);
        return $return2;
    }

    /**
     * @return Convention[] Returns an array of Convention objects
     */
    public function getListAttenteVisa($delaiMin = 10)
    {
        $return = [];
        $entities = $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')->setParameter('val', $value)
            ->orderBy('c.numero', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        $i = 0;
        foreach ($entities as $entity):
            /* @var $entity Convention */
            if ($entity->getDelaiAttenteVisa() > $delaiMin) {
                $return[$i]['entity'] = $entity;
                $return[$i]["delaiConsomme"] = $entity->getDelaiAttenteVisa();
                $return[$i]["numero"] = $entity->getNumero();
            }
            $i++;
        endforeach;
        //dd($i);
        //dd($order);
        //dump($return);
        $order = "delaiConsomme";
        usort($return, function($a, $b) use ($order) {
            return ($b[$order] < $a[$order]) ? -1 : 1;
        });
        $return2 = [];
        foreach( $return as $r):
            $return2[] = $r['entity'];
        endforeach;

        //dd($return2);
        return $return2;
    }



    /*
    public function findOneBySomeField($value): ?Convention
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
