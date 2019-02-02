<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Tests\Functionnal;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MovieUserControllerTest extends WebTestCase
{
    public const MOVIE_ONE = 'tt0000001';
    public const MOVIE_THREE = 'tt0000003';

    public function setUp(): void
    {
        $kernel = self::bootKernel();
    }


    public function testMustObtainASuccessWhenMovieIsCorrectlyAdded()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user/1/movie/' . self::MOVIE_ONE,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );

        $serializer = self::$kernel->getContainer()->get('serializer');

        $this->assertInstanceOf(Movie::class,  $serializer->deserialize($client->getResponse()->getContent(), Movie::class, 'json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainASuccessNoCotentWhenMovieIsCorrectlyDeleted()
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/user/1/movie/' . self::MOVIE_ONE,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );

        $this->assertEquals('',  $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenMovieIsAlreadySelectedByUser()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user/3/movie/' . self::MOVIE_THREE,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );

        $this->assertEquals('{"msg":"This movie is already selected by user"}',  $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenUserHaveAlreadyThreeMovies()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user/2/movie/' . self::MOVIE_ONE,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );

        $this->assertEquals('{"msg":"User must be have a maximum of 3 movies"}',  $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenUserIsntFound()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user/25/movie/' . self::MOVIE_ONE,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );

        $this->assertEquals('{"msg":"User not found"}',  $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenAcceptHeaderIsntValid()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user/1/movie/' . self::MOVIE_ONE,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/csv'
            ]
        );

        $this->assertEquals('{"msg":"Accept isn\'t accepted,  accepted application\/json"}',  $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $client->getResponse()->getStatusCode());
    }
}
