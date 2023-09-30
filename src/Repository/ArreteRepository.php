<?php

namespace App\Repository;

use App\Entity\Arrete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Arrete|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arrete|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arrete[]    findAll()
 * @method Arrete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArreteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arrete::class);
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

    // /**
    //  * @return Arrete[] Returns an array of Arrete objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Arrete
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
