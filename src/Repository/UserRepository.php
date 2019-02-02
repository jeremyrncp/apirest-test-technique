<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function countMovies(User $user): int
    {
        $qb = $this->createQueryBuilder('u')
                   ->where('u.id = :idUser')
                   ->join('u.Movies', 'm')
                   ->setParameter('idUser', $user->getId())
                   ->getQuery()
             ;

        return count($qb->getArrayResult());
    }

    public function haveMovie(Movie $movie, User $user): bool
    {
        return $this->createQueryBuilder('u')
                    ->join('u.Movies', 'm')
                    ->where('u.id = :idUser')
                    ->andWhere('m.imdbID = :imdbID')
                    ->setParameter('idUser', $user->getId())
                    ->setParameter('imdbID', $movie->getImdbID())
                    ->getQuery()
                    ->getOneOrNullResult() ? true : false;
    }
}
