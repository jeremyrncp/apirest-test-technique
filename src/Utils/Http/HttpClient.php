<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Utils\Http;

use \GuzzleHttp\Client;

class HttpClient implements HttpRequestInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get(string $uri): string
    {
        // TODO: valider l'uri
        $response = $this->client->request('GET', $uri);

        return $response->getBody()->getContents();
    }
}
