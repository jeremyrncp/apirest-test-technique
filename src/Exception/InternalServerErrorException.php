<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalServerErrorException extends HttpException
{
    public function __construct(string $message = null, \Exception $previous = null, array $headers = array(), ?int $code = 0)
    {
        parent::__construct(500, $message, $previous, $headers, $code);
    }
}
