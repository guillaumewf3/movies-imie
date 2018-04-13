<?php

namespace App\Repository;

use App\Entity\WatchlistItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WatchlistItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method WatchlistItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method WatchlistItem[]    findAll()
 * @method WatchlistItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WatchlistItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WatchlistItem::class);
    }

//    /**
//     * @return WatchlistItem[] Returns an array of WatchlistItem objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WatchlistItem
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
