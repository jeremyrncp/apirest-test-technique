<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Exception\MovieChoiceException;
use App\Entity\User;
use App\Entity\Movie;

class UserServiceTest extends KernelTestCase
{
    public const EMAIL_USER_HAVE_THREE_MOVIES = 'cinefan@api.com';
    public const EMAIL_USER_HAVE_TWO_MOVIES = 'cinetwomovies@api.com';
    public const IMDB_ID = 'tt0000003';

    /**
     * @var Movie
     */
    private $movieNumberThree;

    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;


    public function setUp(): void
    {
        self::bootKernel();
        $this->movieNumberThree = $this->getMovie();
        $this->userRepository = self::$kernel
                                    ->getContainer()
                                    ->get('doctrine')
                                    ->getRepository(User::class)
        ;
        $this->entityManager = self::$kernel
                                    ->getContainer()
                                    ->get('doctrine.orm.default_entity_manager');
    }

    public function testMustObtainAnErrorWhenUserRemoveMovieNotSelected()
    {
        $this->expectException(MovieChoiceException::class);

        $userService = new UserService($this->userRepository, $this->entityManager);
        $userService->removeMovie($this->getMovieOne(), $this->getUserByEmail(self::EMAIL_USER_HAVE_TWO_MOVIES));
    }

    public function testMustObtainAnErroWhenUserHaveSelectedAMovieAlreadyInHerChoices()
    {
        $this->expectException(MovieChoiceException::class);

        $userService = new UserService($this->userRepository, $this->entityManager);
        $userService->addMovie($this->movieNumberThree, $this->getUserByEmail(self::EMAIL_USER_HAVE_TWO_MOVIES));
    }

    public function testMustObtainAnErroWhenUserHaveSelectedAMaximumNumberOfMovies()
    {
        $this->expectException(MovieChoiceException::class);

        $userService = new UserService($this->userRepository, $this->entityManager);
        $userService->addMovie($this->movieNumberThree, $this->getUserByEmail(self::EMAIL_USER_HAVE_THREE_MOVIES));
    }

    private function getMovieOne(): Movie
    {
        $movieRepository = self::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Movie::class)
        ;

        return $movieRepository->findOneBy(
            ['imdbID' => 'tt0000001']
        );
    }

    private function getMovie(): Movie
    {
        $movieRepository = self::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Movie::class)
        ;

        return $movieRepository->findOneBy(
            ['imdbID' => self::IMDB_ID]
        );
    }

    private function getUserByEmail(string $email): User
    {
        $userRepository = self::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
        ;

        return $userRepository->findOneBy(
            ['email' => $email]
        );
    }
}
