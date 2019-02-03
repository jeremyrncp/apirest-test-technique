<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Utils\Http;


interface HttpRequestInterface
{
    public function get(string $uri): string;
}
