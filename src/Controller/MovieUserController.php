<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Exception\InvalidParameterException;
use App\Exception\InvalidWebServiceException;
use App\Exception\MovieChoiceException;
use App\Service\Movie\MovieService;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;


class MovieUserController extends UserController
{
    /**
     * @Route("/api/user/{userID}/movie", requirements={"userID"="\d+"}, name="movies_user", methods={"GET"})
     *
     * @param Request $request
     * @param int $userID
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     *
     * @return Response
     */
    public function getMoviesUser(
        Request $request,
        int $userID,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ) {
        $this->isValidAccept($request);

        $user = $this->fetchUser($userID, $entityManager);

        if (0 === $user->getMovies()->count()) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return new Response(
            $serializer->serialize($user->getMovies(), self::FORMAT),
            Response::HTTP_OK
        );
    }



    /**
     * @Route("/api/user/{userID}/movie/{imdbID}", requirements={"userID"="\d+"}, name="add_movie_choice_user", methods={"DELETE", "POST"})
     *
     *
     * @param Request $request
     * @param int $userID
     * @param string $imdbID
     * @param MovieService $movieService
     * @param UserService $userService
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return Response
     * @throws InvalidWebServiceException
     */
    public function deleteOrAddMovieUser(
        Request $request,
        int $userID,
        string $imdbID,
        MovieService $movieService,
        UserService $userService,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ) {
        try {
            $this->isValidAccept($request);

            $movie = $movieService->getMovie($imdbID);
            $user = $this->fetchUser($userID, $entityManager);

            if ($request->isMethod('DELETE')) {
                return $this->removeMovie($movie, $user, $userService, $entityManager);
            }

            return $this->addMovie($movie, $user, $userService, $entityManager, $serializer);

        } catch (InvalidParameterException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        } catch (MovieChoiceException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    /**
     * @param Movie $movie
     * @param User $user
     * @param UserService $userService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws MovieChoiceException
     */
    private function removeMovie(Movie $movie, User $user, UserService $userService, EntityManagerInterface $entityManager)
    {
        $userService->removeMovie($movie, $user);
        $entityManager->flush();

        return new Response('',
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param Movie $movie
     * @param User $user
     * @param UserService $userService
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return Response
     * @throws MovieChoiceException
     */
    private function addMovie(
        Movie $movie,
        User $user,
        UserService $userService,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ) {
        $userService->addMovie($movie, $user);
        $entityManager->flush();

        return new Response($serializer->serialize(
            $movie,
            self::FORMAT
        ),
            Response::HTTP_OK
        );
    }
}
