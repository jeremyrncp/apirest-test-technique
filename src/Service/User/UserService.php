<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Service\User;

use App\Entity\Movie;
use App\Entity\User;
use App\Exception\MovieChoiceException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public const MAX_MOVIES_SELECTED = 3;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Movie $movie
     * @param User $user
     *
     * @throws MovieChoiceException
     */
    public function addMovie(Movie $movie, User $user)
    {
        $this->userCanAddMovie($user, $movie);

        $user->addMovie($movie);
        $this->entityManager->persist($user);
    }


    /**
     * @param Movie $movie
     * @param User $user
     *
     * @throws MovieChoiceException
     */
    public function removeMovie(Movie $movie, User $user)
    {
        if (!$this->userRepository->haveMovie($movie, $user)) {
            throw new MovieChoiceException('This movie isn\'t selected by user');
        }

        $user->removeMovie($movie);
        $this->entityManager->persist($user);
    }

    /**
     * @param User $user
     * @param Movie $movie
     * @throws MovieChoiceException
     */
    private function userCanAddMovie(User $user, Movie $movie): void
    {
        if ($this->userRepository->countMovies($user) >= self::MAX_MOVIES_SELECTED) {
            throw new MovieChoiceException(
                sprintf('User must be have a maximum of %d movies', self::MAX_MOVIES_SELECTED)
            );
        }

        if ($this->userRepository->haveMovie($movie, $user)) {
            throw new MovieChoiceException('This movie is already selected by user');
        }
    }
}
