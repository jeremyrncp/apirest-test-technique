<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Service\Movie;

use App\DTO\MovieOmdbDTO;
use App\Entity\Movie;
use App\Exception\InvalidParameterException;
use App\Exception\InvalidWebServiceException;
use App\Form\MovieOmdbType;
use App\Utils\Http\HttpRequestInterface;
use App\Utils\Symfony\FormUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class MovieService
{
    public const OMDB_API_BASE = 'http://www.omdbapi.com/?i=';

    /**
     * @var HttpRequestInterface
     */
    private $httpRequest;

    /**
     * @var string
     */
    private $omdbApiKey;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * MovieService constructor.
     * @param HttpRequestInterface $httpRequest
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        HttpRequestInterface $httpRequest,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager
    ) {
        $this->httpRequest = $httpRequest;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
    }

    public function setOmdbApiKey(string $omdbApiKey)
    {
        $this->omdbApiKey = $omdbApiKey;
    }

    /**
     * @param string $imdbId
     * @return Movie|null|object
     * @throws InvalidParameterException
     * @throws InvalidWebServiceException
     */
    public function getMovie(string $imdbId)
    {
        $this->isValidImdbId($imdbId);

        $movie = $this->manager->getRepository(Movie::class)->findOneBy(
            ['imdbID' => $imdbId]
        );

        if ($movie instanceof Movie) {
            return $movie;
        }

        $movie = $this->getMovieWithWebService($imdbId);
        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }

    /**
     * @param string $imdbId
     * @return Movie
     * @throws InvalidWebServiceException
     */
    private function getMovieWithWebService(string $imdbId): Movie
    {
        $movieInformations = json_decode(
            $this->httpRequest->get($this->getUriOmdbApi($imdbId)),
        true
        );

        $movieOmdbDTO = new MovieOmdbDTO($this->httpRequest);
        $movieOmDbForm = $this->formFactory->create(MovieOmdbType::class, $movieOmdbDTO);
        $movieOmDbForm->submit($movieInformations);

        if ($movieOmDbForm->isSubmitted() && $movieOmDbForm->isValid()) {
            return $movieOmdbDTO->getMovie();
        }

        throw new InvalidWebServiceException(
            sprintf('Movie web service result isn\'t valid : %s', FormUtils::errorsToString(
                $movieOmDbForm->getErrors()
            ))
        );
    }

    /**
     * @param string $imdbId
     * @return string
     */
    private function getUriOmdbApi(string $imdbId): string
    {
        return self::OMDB_API_BASE . $imdbId . '&apikey=' . $this->omdbApiKey;
    }

    /**
     * @param string $imdbId
     * @throws InvalidParameterException
     */
    private function isValidImdbId(string $imdbId): void
    {
        if (1 !== preg_match('/^\b(tt|nm|co|ev|ch|nl)([0-9]{7})\b$/i', $imdbId, $imdMatch)) {
            throw new InvalidParameterException("ImdId isn't valid");
        }
    }
}
