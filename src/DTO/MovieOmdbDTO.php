<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\DTO;


use App\Entity\Movie;
use App\Utils\Http\HttpRequestInterface;

class MovieOmdbDTO
{
    /**
     * @var string
     */
    private $imdbID;

    /**
     * @var string
     */
    private $Title;

    /**
     * @var string
     */
    private $Poster;

    /**
     * @var HttpRequestInterface
     */
    private $httpRequest;

    /**
     * MovieOmdbDTO constructor.
     * @param HttpRequestInterface $httpRequest
     */
    public function __construct(HttpRequestInterface $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->Title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->Title = $title;
    }

    /**
     * @return string
     */
    public function getPoster(): ?string
    {
        return $this->Poster;
    }

    /**
     * @param string $poster
     */
    public function setPoster(string $poster): void
    {
        $this->Poster = $poster;
    }

    /**
     * @return string
     */
    public function getImdbID(): ?string
    {
        return $this->imdbID;
    }

    /**
     * @param string $imdbID
     */
    public function setImdbID(string $imdbID): void
    {
        $this->imdbID = $imdbID;
    }

    public function getMovie()
    {
        $movie = new Movie();
        $movie->setTitle($this->Title);
        $movie->setImdbID($this->imdbID);
        $movie->setPoster($this->getPosterInBase64());

        return $movie;
    }

    private function getPosterInBase64(): string
    {
        return base64_encode($this->httpRequest->get($this->Poster));
    }
}
