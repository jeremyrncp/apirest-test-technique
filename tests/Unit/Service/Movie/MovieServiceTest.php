<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

use App\Service\Movie\MovieService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use \App\Exception\InvalidParameterException;

class MovieServiceTest extends KernelTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testMustObtainAValidMovieWhenImdbIdIsValid()
    {
        $movie = $this->getMovieService()->getMovie('tt1285016');

        $this->assertInstanceOf(\App\Entity\Movie::class, $movie);
    }


    public function testShouldObtainAnErrorWhenImdbIdIsntValid()
    {
        $this->expectException(InvalidParameterException::class);

        $movieService = $this->getMovieService();
        $movieService->getMovie('test');
    }

    private function getMovieService(): MovieService
    {
        return self::$kernel->getContainer()->get('movie.service');
    }
}
