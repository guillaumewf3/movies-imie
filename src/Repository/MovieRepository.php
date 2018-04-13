<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findPaginated($page = 1, $keyword = null, $minYear = null, $maxYear = null)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->addOrderBy('m.rating', 'DESC');
        $qb->leftJoin('m.reviews', 'r')->addSelect('r');
        $qb->setMaxResults(50);
        $qb->setFirstResult(($page-1)*50);

        if ($keyword){
            $qb->andWhere("m.title LIKE :kw OR m.actors LIKE :kw");
            $qb->setParameter('kw', '%' . $keyword . '%');
        }
        if ($minYear){
            $qb->andWhere("m.year >= :minYear");
            $qb->setParameter('minYear', $minYear);
        }
        if ($maxYear){
            $qb->andWhere("m.year <= :maxYear");
            $qb->setParameter('maxYear', $maxYear);
        }

        $query = $qb->getQuery();
        //$results = $query->getResult();
        return new Paginator($query);
    }



//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
