<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    public function findBestMovie(): ?Movie
    {
        $qb = $this->createQueryBuilder('m');

        return $qb ->innerJoin('m.users', 'u')
                   ->groupBy('m')
                   ->orderBy(
                       $qb->expr()->count('m'), 'DESC'
                    )
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
