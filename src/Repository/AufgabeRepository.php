<?php

namespace App\Repository;

use App\Entity\Aufgabe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Aufgabe>
 *
 * @method Aufgabe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aufgabe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aufgabe[]    findAll()
 * @method Aufgabe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AufgabeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aufgabe::class);
    }

//    /**
//     * @return Aufgabe[] Returns an array of Aufgabe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Aufgabe
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
