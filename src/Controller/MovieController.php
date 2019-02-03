<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;


class MovieController extends ApiController
{
    public const MOST_SELECTED_MOVIE = 'mostselected';

    /**
     * @Route("/api/movie", name="movies", methods={"GET"})
     *
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param MovieRepository $movieRepository
     * @return Response
     */
    public function getMovie(
        Request $request,
        SerializerInterface $serializer,
        MovieRepository $movieRepository
    ): Response {
        $this->isValidAccept($request);

        if ($request->query->has('sort') && $request->query->get('sort') === self::MOST_SELECTED_MOVIE) {
            $movie = $movieRepository->findBestMovie();
        } else {
            $movie = $movieRepository->findAll();
        }

        return new Response(
            $serializer->serialize($movie, self::FORMAT),
            Response::HTTP_OK
        );
    }
}
