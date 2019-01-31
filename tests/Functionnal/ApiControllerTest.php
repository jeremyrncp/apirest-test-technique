<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Tests\Functionnal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    public function testMustObtainAnErrorWhenContentFormatIsAcceptedbutIsntValid()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            '<html>'
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenContentFormatIsntAcceptedByApi()
    {
        $client = static::createClient(
        );
        $client->request('POST', '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/csv'
            ]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenContentFormatIsntDefined()
    {
        $client = static::createClient();
        $client->request('POST', '/api/user');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }
}
