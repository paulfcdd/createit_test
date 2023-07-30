<?php

namespace App\Repository;

use App\Entity\WeatherSearchHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherSearchHistory>
 *
 * @method WeatherSearchHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherSearchHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherSearchHistory[]    findAll()
 * @method WeatherSearchHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherSearchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherSearchHistory::class);
    }

//    /**
//     * @return WeatherSearchHistory[] Returns an array of WeatherSearchHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WeatherSearchHistory
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
